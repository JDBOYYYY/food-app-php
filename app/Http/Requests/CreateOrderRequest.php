<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Order; // For status constants
use Illuminate\Support\Facades\Auth; // For authorization

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Assuming ShippingAddressId is an ID of an existing Address record belonging to the user
            'ShippingAddressId' => 'required|integer|exists:Addresses,Id',
            // Assuming BillingAddressId is also an ID of an existing Address record
            'BillingAddressId' => 'nullable|integer|exists:Addresses,Id',
            // 'Notes' => 'nullable|string|max:1000', // Example if you add notes

            // Order items
            'items' => 'required|array|min:1', // Must have at least one item
            'items.*.ProductId' => 'required|integer|exists:Products,Id', // Each item must have a valid ProductId
            'items.*.Quantity' => 'required|integer|min:1', // Each item must have a quantity of at least 1

            // Status will likely be set by the system, e.g., to 'Pending' by default.
            // TotalAmount will be calculated in the controller.
            // UserId will come from the authenticated user.
            // OrderDate will be set by the system.
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'ShippingAddressId.required' => 'A shipping address is required.',
            'ShippingAddressId.exists' => 'The selected shipping address is invalid.',
            'BillingAddressId.exists' => 'The selected billing address is invalid.',
            'items.required' => 'Your order must contain at least one item.',
            'items.min' => 'Your order must contain at least one item.',
            'items.*.ProductId.required' => 'Each item must have a product specified.',
            'items.*.ProductId.exists' => 'One of the products selected is invalid.',
            'items.*.Quantity.required' => 'Each item must have a quantity.',
            'items.*.Quantity.min' => 'Item quantity must be at least 1.',
        ];
    }

    /**
     * Prepare the data for validation.
     * (Optional: could be used to ensure UserId is set from Auth user if not using controller)
     */
    // protected function prepareForValidation(): void
    // {
    //     if (Auth::check()) {
    //         $this->merge([
    //             'UserId' => Auth::id(),
    //         ]);
    //     }
    // }
}