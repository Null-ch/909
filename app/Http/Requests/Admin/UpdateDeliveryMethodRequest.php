<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class UpdateDeliveryMethodRequest extends StoreDeliveryMethodRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var \App\Models\DeliveryMethod $method */
        $method = $this->route('delivery_method');

        return $this->sharedRules($method->id);
    }
}
