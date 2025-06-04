<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // For more complex rules like unique ignoring current model

class UpdateRestaurantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Placeholder: For now, allow anyone.
        // Replace with actual authorization logic.
        // Example:
        // $user = $this->user();
        // return $user && $user->isAdmin();
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // $this->route('restaurant') will give us the Restaurant model instance
        // due to route-model binding, or just the ID if not bound.
        $restaurantId = $this->route('restaurant') ? ($this->route('restaurant')->Id ?? null) : null;

        return [
            // 'sometimes' means the rule is only applied if the field is present in the request.
            // Good for PATCH requests where only some fields are sent.
            // For PUT (full replacement), you might make them all 'required'.
            'Name' => [
                'sometimes',
                'required',
                'string',
                'max:150',
                Rule::unique('Restaurants', 'Name')->ignore($restaurantId, 'Id'), // Name must be unique, ignoring the current restaurant
            ],
            'ImageUrl' => 'nullable|string|max:1024|url',
            'AverageRating' => 'nullable|numeric|min:0|max:5',
            'DeliveryTime' => 'nullable|string|max:50',
            'Distance' => 'nullable|string|max:50',
            'PriceRange' => 'nullable|string|max:50',

            'CategoryIds' => 'nullable|array', // Allow updating categories
            'CategoryIds.*' => 'sometimes|integer|exists:Categories,Id',
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
            'Name.unique' => 'A restaurant with this name already exists.',
            'CategoryIds.*.exists' => 'One or more selected categories are invalid.',
            'ImageUrl.url' => 'The image URL must be a valid URL.',
        ];
    }
}