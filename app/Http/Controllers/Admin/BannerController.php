<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBannerRequest;
use App\Http\Requests\Admin\UpdateBannerRequest;
use App\Models\Banner;
use App\Services\BannerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function __construct(
        private readonly BannerService $bannerService,
    ) {}

    public function index(): View
    {
        return view('admin.banners.index', [
            'banners' => $this->bannerService->all(),
        ]);
    }

    public function create(): View
    {
        return view('admin.banners.create', [
            'nextSortOrder' => $this->bannerService->nextSortOrder(),
        ]);
    }

    public function store(StoreBannerRequest $request): RedirectResponse
    {
        $this->bannerService->create(
            $request->safe()->except('image'),
            $request->file('image'),
        );

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Баннер успешно создан.');
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.edit', [
            'banner' => $banner,
        ]);
    }

    public function update(UpdateBannerRequest $request, Banner $banner): RedirectResponse
    {
        $this->bannerService->update(
            $banner,
            $request->safe()->except('image'),
            $request->file('image'),
        );

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Баннер успешно обновлён.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $this->bannerService->delete($banner);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Баннер удалён.');
    }

    public function moveUp(Banner $banner): RedirectResponse
    {
        $this->bannerService->moveUp($banner);

        return redirect()->route('admin.banners.index');
    }

    public function moveDown(Banner $banner): RedirectResponse
    {
        $this->bannerService->moveDown($banner);

        return redirect()->route('admin.banners.index');
    }
}
