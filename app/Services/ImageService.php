<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageService
{
    public function storeCategoryImage(UploadedFile $file): string
    {
        return $this->storeResizedImage($file, 'categories', 800, 600, false);
    }

    public function storeProductImage(UploadedFile $file): string
    {
        return $this->storeResizedImage($file, 'products', 1200, 1200, false);
    }

    public function storeProductThumbnail(UploadedFile $file): string
    {
        return $this->storeResizedImage($file, 'products/thumbs', 50, 50);
    }

    public function storeSettingLogo(UploadedFile $file): string
    {
        return $this->storeResizedImage($file, 'settings', 400, 200, false);
    }

    public function storeBannerImage(UploadedFile $file): string
    {
        return $this->storeResizedImage($file, 'banners', 1920, 700);
    }

    public function storeSettingFavicon(UploadedFile $file): string
    {
        return $this->storeResizedImage($file, 'settings/favicons', 64, 64);
    }

    private function storeResizedImage(UploadedFile $file, string $directory, int $width, int $height, bool $cover = true): string
    {
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $directory.'/'.$filename;
        $fullPath = Storage::disk('public')->path($path);

        Storage::disk('public')->makeDirectory($directory);

        if ($cover) {
            $this->resizeToCover($file->getPathname(), $fullPath, $width, $height);
        } else {
            $this->resizeToFit($file->getPathname(), $fullPath, $width, $height);
        }

        return $path;
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function resizeToCover(string $source, string $destination, int $width, int $height): void
    {
        $info = \getimagesize($source);

        if ($info === false) {
            throw new RuntimeException('Не удалось прочитать изображение.');
        }

        [$srcWidth, $srcHeight, $type] = $info;

        $sourceImage = match ($type) {
            \IMAGETYPE_JPEG => \imagecreatefromjpeg($source),
            \IMAGETYPE_PNG => \imagecreatefrompng($source),
            \IMAGETYPE_WEBP => \imagecreatefromwebp($source),
            \IMAGETYPE_GIF => \imagecreatefromgif($source),
            default => throw new RuntimeException('Неподдерживаемый формат изображения.'),
        };

        if ($sourceImage === false) {
            throw new RuntimeException('Не удалось обработать изображение.');
        }

        $scale = max($width / $srcWidth, $height / $srcHeight);
        $scaledWidth = (int) round($srcWidth * $scale);
        $scaledHeight = (int) round($srcHeight * $scale);

        $scaled = \imagecreatetruecolor($scaledWidth, $scaledHeight);

        if (in_array($type, [\IMAGETYPE_PNG, \IMAGETYPE_WEBP, \IMAGETYPE_GIF], true)) {
            \imagealphablending($scaled, false);
            \imagesavealpha($scaled, true);
        }

        \imagecopyresampled(
            $scaled,
            $sourceImage,
            0,
            0,
            0,
            0,
            $scaledWidth,
            $scaledHeight,
            $srcWidth,
            $srcHeight
        );

        $cropX = (int) round(($scaledWidth - $width) / 2);
        $cropY = (int) round(($scaledHeight - $height) / 2);

        $canvas = \imagecreatetruecolor($width, $height);

        if (in_array($type, [\IMAGETYPE_PNG, \IMAGETYPE_WEBP, \IMAGETYPE_GIF], true)) {
            \imagealphablending($canvas, false);
            \imagesavealpha($canvas, true);
        }

        \imagecopy(
            $canvas,
            $scaled,
            0,
            0,
            $cropX,
            $cropY,
            $width,
            $height
        );

        match ($type) {
            \IMAGETYPE_JPEG => \imagejpeg($canvas, $destination, 90),
            \IMAGETYPE_PNG => \imagepng($canvas, $destination, 8),
            \IMAGETYPE_WEBP => \imagewebp($canvas, $destination, 90),
            \IMAGETYPE_GIF => \imagegif($canvas, $destination),
            default => null,
        };

        \imagedestroy($sourceImage);
        \imagedestroy($scaled);
        \imagedestroy($canvas);
    }

    private function resizeToFit(string $source, string $destination, int $maxWidth, int $maxHeight): void
    {
        $info = \getimagesize($source);

        if ($info === false) {
            throw new RuntimeException('Не удалось прочитать изображение.');
        }

        [$srcWidth, $srcHeight, $type] = $info;
        $scale = min($maxWidth / $srcWidth, $maxHeight / $srcHeight, 1);
        $targetWidth = (int) round($srcWidth * $scale);
        $targetHeight = (int) round($srcHeight * $scale);

        $sourceImage = match ($type) {
            \IMAGETYPE_JPEG => \imagecreatefromjpeg($source),
            \IMAGETYPE_PNG => \imagecreatefrompng($source),
            \IMAGETYPE_WEBP => \imagecreatefromwebp($source),
            \IMAGETYPE_GIF => \imagecreatefromgif($source),
            default => throw new RuntimeException('Неподдерживаемый формат изображения.'),
        };

        if ($sourceImage === false) {
            throw new RuntimeException('Не удалось обработать изображение.');
        }

        $canvas = \imagecreatetruecolor($targetWidth, $targetHeight);

        if (in_array($type, [\IMAGETYPE_PNG, \IMAGETYPE_WEBP, \IMAGETYPE_GIF], true)) {
            \imagealphablending($canvas, false);
            \imagesavealpha($canvas, true);
        }

        \imagecopyresampled($canvas, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $srcWidth, $srcHeight);

        match ($type) {
            \IMAGETYPE_JPEG => \imagejpeg($canvas, $destination, 90),
            \IMAGETYPE_PNG => \imagepng($canvas, $destination, 8),
            \IMAGETYPE_WEBP => \imagewebp($canvas, $destination, 90),
            \IMAGETYPE_GIF => \imagegif($canvas, $destination),
            default => null,
        };

        \imagedestroy($sourceImage);
        \imagedestroy($canvas);
    }
}
