<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRestaurantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Name' => 'required|string|max:150|unique:Restaurants,Name',
            'ImageUrl' => 'nullable|string|max:1024|url',
            'AverageRating' => 'nullable|numeric|min:0|max:5',
            'DeliveryTime' => 'nullable|string|max:50',
            'Distance' => 'nullable|string|max:50',
            'PriceRange' => 'nullable|string|max:50',
            'CategoryIds' => 'nullable|array',
            'CategoryIds.*' => 'integer|exists:Categories,Id',
        ];
    }

    public function messages(): array
    {
        return [
            'Name.unique' => 'A restaurant with this name already exists.',
            'CategoryIds.*.exists' =>
                'One or more selected categories are invalid.',
            'ImageUrl.url' => 'The image URL must be a valid URL.',
        ];
    }
}