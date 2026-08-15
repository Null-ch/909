<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Services\SettingService;
use App\Support\SettingDefinitions;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(
        private readonly SettingService $settingService,
    ) {}

    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settingsValues' => $this->settingService->getValuesForForm(),
            'groupLabels' => SettingDefinitions::groupLabels(),
        ]);
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $this->settingService->update(
            $request->validated(),
            $request->allFiles(),
        );

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Настройки успешно сохранены.');
    }
}
