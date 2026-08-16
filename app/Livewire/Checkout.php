<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\AccountService;
use App\Services\CartService;
use App\Services\DeliveryCalculatorService;
use App\Services\OrderService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use RuntimeException;

class Checkout extends Component
{
    public string $name = '';

    public string $phone = '';

    public string $email = '';

    public string $address = '';

    public string $comment = '';

    public ?int $deliveryMethodId = null;

    public ?string $errorMessage = null;

    public function mount(CartService $cartService, DeliveryCalculatorService $deliveryCalculatorService): void
    {
        if ($cartService->getCurrentItems()->isEmpty()) {
            $this->redirect('/cart');

            return;
        }

        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = (string) $user->phone;
        }

        $cheapest = $this->deliveryOptions($cartService, $deliveryCalculatorService)->sortBy('price')->first();
        $this->deliveryMethodId = $cheapest['method']->id ?? null;
    }

    public function placeOrder(
        CartService $cartService,
        DeliveryCalculatorService $deliveryCalculatorService,
        OrderService $orderService,
        AccountService $accountService,
    ): void {
        $this->errorMessage = null;
        $this->validate();

        $cartItems = $cartService->getCurrentItems();

        if ($cartItems->isEmpty()) {
            $this->redirect('/cart');

            return;
        }

        $delivery = $this->deliveryOptions($cartService, $deliveryCalculatorService, $cartItems)
            ->first(fn ($option) => $option['method']->id === $this->deliveryMethodId);

        if (! $delivery) {
            $this->addError('deliveryMethodId', 'Выберите способ доставки.');

            return;
        }

        $accountStatus = 'existing_session';

        if (! Auth::check()) {
            $existingUser = User::query()->where('email', $this->email)->first();

            if ($existingUser) {
                $accountStatus = 'existing_account';
            } else {
                $result = $accountService->createAccount([
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                ]);

                Auth::login($result['user']);
                $accountService->sendCredentialsEmail($result['user'], $result['password']);

                $accountStatus = 'created';
            }
        }

        try {
            $order = $orderService->createFromCart(
                customer: [
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'address' => $this->address,
                    'comment' => $this->comment,
                ],
                deliveryMethod: $delivery['method'],
                rate: $delivery['rate'],
                cartItems: $cartItems,
            );
        } catch (RuntimeException $e) {
            $this->errorMessage = $e->getMessage();

            return;
        }

        $cartService->clear();
        $this->dispatch('cart-updated');

        $this->redirect('/order/'.$order->order_number.'?account='.$accountStatus);
    }

    public function render(CartService $cartService, DeliveryCalculatorService $deliveryCalculatorService)
    {
        $cartItems = $cartService->getCurrentItems();
        $itemsTotal = (float) $cartItems->sum(fn ($item) => $item->lineTotal());
        $deliveryOptions = $this->deliveryOptions($cartService, $deliveryCalculatorService, $cartItems);
        $deliveryPrice = $deliveryOptions->first(fn ($option) => $option['method']->id === $this->deliveryMethodId)['price'] ?? 0.0;

        return view('livewire.checkout', [
            'cartItems' => $cartItems,
            'itemsTotal' => $itemsTotal,
            'deliveryOptions' => $deliveryOptions,
            'deliveryPrice' => $deliveryPrice,
            'grandTotal' => $itemsTotal + $deliveryPrice,
        ])->layout('layouts.app', [
            'metaTitle' => 'Оформление заказа — '.setting('shop_name'),
        ]);
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'deliveryMethodId' => ['required', 'integer'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => 'имя',
            'phone' => 'телефон',
            'email' => 'email',
            'address' => 'адрес доставки',
            'deliveryMethodId' => 'способ доставки',
        ];
    }

    /**
     * @return Collection<int, array{method: \App\Models\DeliveryMethod, rate: \App\Models\DeliveryRate, price: float, label: string}>
     */
    private function deliveryOptions(
        CartService $cartService,
        DeliveryCalculatorService $deliveryCalculatorService,
        ?Collection $cartItems = null,
    ): Collection {
        $cartItems ??= $cartService->getCurrentItems();

        $items = $cartItems->map(fn ($cartItem) => [
            'product' => $cartItem->product,
            'quantity' => $cartItem->quantity,
        ]);

        return $deliveryCalculatorService->calculateForItems($items);
    }
}
