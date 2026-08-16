<?php

namespace App\Services;

use App\Models\Banner;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class BannerService
{
    public function __construct(
        private readonly ImageService $imageService,
        private readonly HomeService $homeService,
    ) {}

    /**
     * @return Collection<int, Banner>
     */
    public function all(): Collection
    {
        return Banner::query()->orderBy('sort_order')->orderBy('id')->get();
    }

    public function nextSortOrder(): int
    {
        return ((int) Banner::query()->max('sort_order')) + 1;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data, ?UploadedFile $image = null): Banner
    {
        if (blank($data['sort_order'] ?? null)) {
            $data['sort_order'] = $this->nextSortOrder();
        }

        if ($image) {
            $data['image'] = $this->imageService->storeBannerImage($image);
        }

        $banner = Banner::query()->create($data);

        $this->homeService->clearCache();

        logActivity('created', 'Banner', $banner->id, "Создан баннер «{$banner->title}»");

        return $banner;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Banner $banner, array $data, ?UploadedFile $image = null): Banner
    {
        if (blank($data['sort_order'] ?? null)) {
            $data['sort_order'] = $banner->sort_order;
        }

        if ($image) {
            $this->imageService->delete($banner->image);
            $data['image'] = $this->imageService->storeBannerImage($image);
        }

        $banner->update($data);

        $this->homeService->clearCache();

        logActivity('updated', 'Banner', $banner->id, "Обновлён баннер «{$banner->title}»");

        return $banner->refresh();
    }

    public function delete(Banner $banner): void
    {
        $this->imageService->delete($banner->image);

        $name = $banner->title;
        $id = $banner->id;

        $banner->delete();

        $this->homeService->clearCache();

        logActivity('deleted', 'Banner', $id, "Удалён баннер «{$name}»");
    }

    public function moveUp(Banner $banner): void
    {
        $this->swapWithNeighbor($banner, 'up');
    }

    public function moveDown(Banner $banner): void
    {
        $this->swapWithNeighbor($banner, 'down');
    }

    private function swapWithNeighbor(Banner $banner, string $direction): void
    {
        $query = Banner::query()->where(
            'sort_order',
            $direction === 'up' ? '<' : '>',
            $banner->sort_order,
        );

        $neighbor = $direction === 'up'
            ? $query->orderByDesc('sort_order')->orderByDesc('id')->first()
            : $query->orderBy('sort_order')->orderBy('id')->first();

        if (! $neighbor) {
            return;
        }

        $bannerOrder = $banner->sort_order;
        $neighborOrder = $neighbor->sort_order;

        $banner->update(['sort_order' => $neighborOrder]);
        $neighbor->update(['sort_order' => $bannerOrder]);

        $this->homeService->clearCache();
    }
}
