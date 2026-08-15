<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDeliveryMethodRequest;
use App\Http\Requests\Admin\UpdateDeliveryMethodRequest;
use App\Models\DeliveryMethod;
use App\Services\DeliveryMethodService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeliveryMethodController extends Controller
{
    public function __construct(
        private readonly DeliveryMethodService $deliveryMethodService,
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->has('draw')) {
            return response()->json($this->deliveryMethodService->datatable($request));
        }

        return view('admin.delivery-methods.index');
    }

    public function create(): View
    {
        return view('admin.delivery-methods.create');
    }

    public function store(StoreDeliveryMethodRequest $request): RedirectResponse
    {
        $this->deliveryMethodService->create(
            $request->safe()->except('rates'),
            $request->input('rates', []),
        );

        return redirect()
            ->route('admin.delivery-methods.index')
            ->with('success', 'Способ доставки успешно создан.');
    }

    public function edit(DeliveryMethod $deliveryMethod): View
    {
        $deliveryMethod->load('rates');

        return view('admin.delivery-methods.edit', [
            'deliveryMethod' => $deliveryMethod,
        ]);
    }

    public function update(UpdateDeliveryMethodRequest $request, DeliveryMethod $deliveryMethod): RedirectResponse
    {
        $this->deliveryMethodService->update(
            $deliveryMethod,
            $request->safe()->except('rates'),
            $request->input('rates', []),
        );

        return redirect()
            ->route('admin.delivery-methods.index')
            ->with('success', 'Способ доставки успешно обновлён.');
    }

    public function destroy(DeliveryMethod $deliveryMethod): RedirectResponse
    {
        $this->deliveryMethodService->delete($deliveryMethod);

        return redirect()
            ->route('admin.delivery-methods.index')
            ->with('success', 'Способ доставки удалён.');
    }
}
