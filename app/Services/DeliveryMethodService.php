<?php

namespace App\Services;

use App\Models\DeliveryMethod;
use App\Models\DeliveryRate;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeliveryMethodService
{
    /**
     * @return array{draw: int, recordsTotal: int, recordsFiltered: int, data: array<int, array<string, mixed>>}
     */
    public function datatable(Request $request): array
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $search = trim((string) $request->input('search.value', ''));

        $query = DeliveryMethod::query()->withCount('rates');

        $recordsTotal = DeliveryMethod::query()->count();

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        $methods = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->skip($start)
            ->take($length > 0 ? $length : 10)
            ->get();

        return [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $methods->map(fn (DeliveryMethod $method) => [
                'id' => $method->id,
                'name' => e($method->name),
                'rates_count' => $method->rates_count,
                'is_active' => $method->is_active
                    ? '<span class="badge badge-teal">Активен</span>'
                    : '<span class="badge badge-red">Неактивен</span>',
                'actions' => $this->actionsHtml($method),
            ])->all(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<int, array<string, mixed>>  $rates
     */
    public function create(array $data, array $rates): DeliveryMethod
    {
        return DB::transaction(function () use ($data, $rates) {
            $data['slug'] = $this->resolveSlug($data['name'], $data['slug'] ?? null);

            $method = DeliveryMethod::query()->create($data);
            $this->syncRates($method, $rates);

            logActivity('created', 'DeliveryMethod', $method->id, "Создан способ доставки «{$method->name}»");

            return $method->load('rates');
        });
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<int, array<string, mixed>>  $rates
     */
    public function update(DeliveryMethod $method, array $data, array $rates): DeliveryMethod
    {
        return DB::transaction(function () use ($method, $data, $rates) {
            $data['slug'] = $this->resolveSlug($data['name'], $data['slug'] ?? null, $method->id);

            $method->update($data);
            $this->syncRates($method, $rates);

            logActivity('updated', 'DeliveryMethod', $method->id, "Обновлён способ доставки «{$method->name}»");

            return $method->refresh()->load('rates');
        });
    }

    public function delete(DeliveryMethod $method): void
    {
        $name = $method->name;
        $id = $method->id;

        $method->delete();

        logActivity('deleted', 'DeliveryMethod', $id, "Удалён способ доставки «{$name}»");
    }

    /**
     * @param  array<int, array<string, mixed>>  $rates
     */
    private function syncRates(DeliveryMethod $method, array $rates): void
    {
        $method->rates()->delete();

        foreach ($rates as $index => $rate) {
            if (blank($rate['price'] ?? null)) {
                continue;
            }

            $method->rates()->create([
                'name' => $rate['name'] ?? null,
                'min_weight' => $rate['min_weight'] ?? 0,
                'max_weight' => $rate['max_weight'] ?? null,
                'min_volume' => $rate['min_volume'] ?? 0,
                'max_volume' => $rate['max_volume'] ?? null,
                'max_length' => $rate['max_length'] ?? null,
                'max_width' => $rate['max_width'] ?? null,
                'max_height' => $rate['max_height'] ?? null,
                'price' => $rate['price'],
                'is_active' => isset($rate['is_active']),
                'sort_order' => $index,
            ]);
        }
    }

    private function resolveSlug(string $name, ?string $slug, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $name);
        $base = $base !== '' ? $base : 'delivery';
        $candidate = $base;
        $counter = 1;

        while (
            DeliveryMethod::withTrashed()
                ->where('slug', $candidate)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $candidate = $base.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }

    private function actionsHtml(DeliveryMethod $method): string
    {
        $edit = route('admin.delivery-methods.edit', $method);
        $destroy = route('admin.delivery-methods.destroy', $method);
        $token = csrf_token();
        $name = e($method->name);

        return '<div style="display:flex;gap:8px;justify-content:flex-end">'
            .'<a href="'.$edit.'" class="btn btn-sm btn-outline">Изменить</a>'
            .'<form method="POST" action="'.$destroy.'" data-confirm="Удалить способ доставки «'.$name.'»?">'
            .'<input type="hidden" name="_token" value="'.$token.'">'
            .'<input type="hidden" name="_method" value="DELETE">'
            .'<button type="submit" class="btn btn-sm btn-outline" style="color:var(--danger)">Удалить</button>'
            .'</form></div>';
    }
}
