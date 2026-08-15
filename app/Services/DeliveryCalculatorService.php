<?php

namespace App\Services;

use App\Models\DeliveryMethod;
use App\Models\DeliveryRate;
use App\Models\Product;
use Illuminate\Support\Collection;

class DeliveryCalculatorService
{
    /**
     * @param  iterable<int, array{product: Product, quantity: int}>  $items
     * @return Collection<int, array{method: DeliveryMethod, rate: DeliveryRate, price: float, label: string}>
     */
    public function calculateForItems(iterable $items): Collection
    {
        $metrics = $this->aggregateMetrics($items);

        return DeliveryMethod::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with(['activeRates'])
            ->get()
            ->map(function (DeliveryMethod $method) use ($metrics) {
                $rate = $this->findMatchingRate($method->activeRates, $metrics);

                if (! $rate) {
                    return null;
                }

                return [
                    'method' => $method,
                    'rate' => $rate,
                    'price' => (float) $rate->price,
                    'label' => $method->name.' — '.number_format((float) $rate->price, 2, '.', ' ').' ₽',
                ];
            })
            ->filter()
            ->values();
    }

    /**
     * @param  iterable<int, array{product: Product, quantity: int}>  $items
     */
    public function calculateCheapest(iterable $items): ?array
    {
        return $this->calculateForItems($items)
            ->sortBy('price')
            ->first();
    }

    /**
     * @param  Collection<int, DeliveryRate>  $rates
     * @param  array{total_weight: float, total_volume: float, max_length: float, max_width: float, max_height: float}  $metrics
     */
    private function findMatchingRate(Collection $rates, array $metrics): ?DeliveryRate
    {
        return $rates
            ->sortBy('sort_order')
            ->first(fn (DeliveryRate $rate) => $this->rateMatches($rate, $metrics));
    }

    /**
     * @param  array{total_weight: float, total_volume: float, max_length: float, max_width: float, max_height: float}  $metrics
     */
    private function rateMatches(DeliveryRate $rate, array $metrics): bool
    {
        if ($metrics['total_weight'] < (float) $rate->min_weight) {
            return false;
        }

        if ($rate->max_weight !== null && $metrics['total_weight'] > (float) $rate->max_weight) {
            return false;
        }

        if ($metrics['total_volume'] > 0) {
            if ($metrics['total_volume'] < (float) $rate->min_volume) {
                return false;
            }

            if ($rate->max_volume !== null && $metrics['total_volume'] > (float) $rate->max_volume) {
                return false;
            }
        }

        if ($rate->max_length !== null && $metrics['max_length'] > (float) $rate->max_length) {
            return false;
        }

        if ($rate->max_width !== null && $metrics['max_width'] > (float) $rate->max_width) {
            return false;
        }

        if ($rate->max_height !== null && $metrics['max_height'] > (float) $rate->max_height) {
            return false;
        }

        return true;
    }

    /**
     * @param  iterable<int, array{product: Product, quantity: int}>  $items
     * @return array{total_weight: float, total_volume: float, max_length: float, max_width: float, max_height: float}
     */
    public function aggregateMetrics(iterable $items): array
    {
        $totalWeight = 0.0;
        $totalVolume = 0.0;
        $maxLength = 0.0;
        $maxWidth = 0.0;
        $maxHeight = 0.0;

        foreach ($items as $item) {
            $product = $item['product'];
            $quantity = (int) $item['quantity'];

            $totalWeight += (float) ($product->weight ?? 0) * $quantity;

            $length = (float) ($product->length ?? 0);
            $width = (float) ($product->width ?? 0);
            $height = (float) ($product->height ?? 0);

            if ($length > 0 && $width > 0 && $height > 0) {
                $totalVolume += ($length / 100) * ($width / 100) * ($height / 100) * $quantity;
                $maxLength = max($maxLength, $length);
                $maxWidth = max($maxWidth, $width);
                $maxHeight = max($maxHeight, $height);
            }
        }

        return [
            'total_weight' => round($totalWeight, 2),
            'total_volume' => round($totalVolume, 4),
            'max_length' => $maxLength,
            'max_width' => $maxWidth,
            'max_height' => $maxHeight,
        ];
    }
}
