<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ShippingAddressId' => 'required|integer|exists:Addresses,Id',
            'BillingAddressId' => 'nullable|integer|exists:Addresses,Id',
            'items' => 'required|array|min:1',
            'items.*.ProductId' => 'required|integer|exists:Products,Id',
            'items.*.Quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'ShippingAddressId.required' => 'A shipping address is required.',
            'ShippingAddressId.exists' =>
                'The selected shipping address is invalid.',
            'BillingAddressId.exists' =>
                'The selected billing address is invalid.',
            'items.required' => 'Your order must contain at least one item.',
            'items.min' => 'Your order must contain at least one item.',
            'items.*.ProductId.required' =>
                'Each item must have a product specified.',
            'items.*.ProductId.exists' =>
                'One of the products selected is invalid.',
            'items.*.Quantity.required' => 'Each item must have a quantity.',
            'items.*.Quantity.min' => 'Item quantity must be at least 1.',
        ];
    }
}