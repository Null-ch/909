<?php

namespace App\Services;

use App\Models\Setting;
use App\Support\SettingDefinitions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    private const CACHE_KEY = 'app.settings.keyed';

    public function __construct(
        private readonly ImageService $imageService,
    ) {}

    public function get(string $key, mixed $default = null): mixed
    {
        $setting = $this->allKeyed()->get($key);

        if ($setting === null) {
            return $default;
        }

        if ($setting->type === 'json') {
            return $setting->decodedValue() ?? $default;
        }

        return $setting->value ?? $default;
    }

    /**
     * @return Collection<string, Setting>
     */
    public function allKeyed(): Collection
    {
        /** @var array<string, array<string, mixed>> $cached */
        $cached = Cache::rememberForever(self::CACHE_KEY, function () {
            return Setting::query()
                ->get()
                ->mapWithKeys(fn (Setting $setting) => [
                    $setting->key => $setting->getAttributes(),
                ])
                ->all();
        });

        return collect($cached)->map(function (array $attributes) {
            $setting = new Setting;
            $setting->forceFill($attributes);
            $setting->exists = true;

            return $setting;
        });
    }

    /**
     * @return array<string, string|null>
     */
    public function getValuesForForm(): array
    {
        $definitions = SettingDefinitions::all();
        $keyed = $this->allKeyed();
        $values = [];

        foreach ($definitions as $key => $definition) {
            $values[$key] = $keyed->get($key)?->value ?? $definition['default'];
        }

        return $values;
    }

    /**
     * @return array<string, Collection<int, Setting>>
     */
    public function groupedForForm(): array
    {
        $grouped = $this->allKeyed()
            ->groupBy('group');

        $result = [];

        foreach (SettingDefinitions::groupLabels() as $group => $label) {
            $result[$group] = $grouped->get($group, collect());
        }

        return $result;
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, UploadedFile>  $files
     */
    public function update(array $data, array $files = []): void
    {
        $definitions = SettingDefinitions::all();

        foreach ($definitions as $key => $definition) {
            if ($definition['type'] === 'image') {
                $this->updateImageSetting($key, $files[$key] ?? null);

                continue;
            }

            if (! array_key_exists($key, $data)) {
                continue;
            }

            $value = $data[$key];

            if ($definition['type'] === 'json' && is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            }

            Setting::query()->updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => $definition['group'],
                    'type' => $definition['type'],
                ],
            );
        }

        $this->clearCache();
        app(HomeService::class)->clearCache();

        logActivity('updated', 'Setting', null, 'Обновлены настройки магазина');
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function seedDefaults(): void
    {
        foreach (SettingDefinitions::all() as $key => $definition) {
            Setting::query()->firstOrCreate(
                ['key' => $key],
                [
                    'value' => $definition['default'],
                    'group' => $definition['group'],
                    'type' => $definition['type'],
                ],
            );
        }

        $this->clearCache();
    }

    private function updateImageSetting(string $key, ?UploadedFile $file): void
    {
        if (! $file) {
            return;
        }

        $definition = SettingDefinitions::all()[$key];
        $existing = Setting::query()->where('key', $key)->first();

        $this->imageService->delete($existing?->value);

        $path = $key === 'favicon'
            ? $this->imageService->storeSettingFavicon($file)
            : $this->imageService->storeSettingLogo($file);

        Setting::query()->updateOrCreate(
            ['key' => $key],
            [
                'value' => $path,
                'group' => $definition['group'],
                'type' => $definition['type'],
            ],
        );
    }
}
