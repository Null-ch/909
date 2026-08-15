<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\DeliveryMethod;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\DeliveryCalculatorService;
use App\Services\OrderService;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::query()->limit(3)->get();
        $user = User::query()->where('email', 'test@example.com')->first();
        $standardDelivery = DeliveryMethod::query()->where('slug', 'standard')->first();
        $expressDelivery = DeliveryMethod::query()->where('slug', 'express')->first();

        if ($products->isEmpty()) {
            return;
        }

        $orders = [
            [
                'user_id' => $user?->id,
                'status' => OrderStatus::New,
                'payment_status' => PaymentStatus::Pending,
                'customer_name' => $user?->name ?? 'Иван Петров',
                'customer_phone' => '+7 (999) 111-22-33',
                'customer_email' => $user?->email ?? 'ivan@example.com',
                'delivery_address' => 'г. Москва, ул. Садовая, д. 10, кв. 5',
                'comment' => 'Позвонить за час до доставки',
                'delivery_method' => $standardDelivery,
            ],
            [
                'user_id' => null,
                'status' => OrderStatus::Processing,
                'payment_status' => PaymentStatus::Paid,
                'customer_name' => 'ООО «Зелёный двор»',
                'customer_phone' => '+7 (495) 123-45-67',
                'customer_email' => 'office@zelenydvor.ru',
                'delivery_address' => 'г. Москва, Промышленная ул., 25',
                'comment' => null,
                'delivery_method' => $expressDelivery,
            ],
            [
                'user_id' => $user?->id,
                'status' => OrderStatus::Shipped,
                'payment_status' => PaymentStatus::Paid,
                'customer_name' => $user?->name ?? 'Тестовый клиент',
                'customer_phone' => '+7 (999) 000-00-01',
                'customer_email' => $user?->email ?? 'test@example.com',
                'delivery_address' => 'г. Москва, ул. Примерная, д. 1',
                'comment' => 'Отправлен клиенту',
                'delivery_method' => $standardDelivery,
            ],
        ];

        $calculator = app(DeliveryCalculatorService::class);

        foreach ($orders as $index => $orderData) {
            $items = $products->take($index + 1);
            $itemsTotal = $items->sum(fn (Product $product) => (float) $product->price);

            $cartItems = $items->map(fn (Product $product) => [
                'product' => $product,
                'quantity' => 1,
            ]);

            $deliveryMethod = $orderData['delivery_method'];
            unset($orderData['delivery_method']);

            $deliveryPrice = 0.0;

            if ($deliveryMethod) {
                $option = $calculator->calculateForItems($cartItems)
                    ->firstWhere(fn (array $option) => $option['method']->id === $deliveryMethod->id);

                $deliveryPrice = $option['price'] ?? 0.0;
            }

            $order = Order::query()->create([
                ...$orderData,
                'order_number' => OrderService::generateOrderNumber(),
                'total_price' => $itemsTotal,
                'delivery_price' => $deliveryPrice,
                'delivery_method_id' => $deliveryMethod?->id,
            ]);

            foreach ($items as $product) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'quantity' => 1,
                    'total' => $product->price,
                ]);
            }
        }
    }
}
