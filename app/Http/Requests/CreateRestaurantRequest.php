<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User; // If you want to use User model for authorization

class CreateRestaurantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * For creating restaurants, typically only admins should be allowed.
     */
    public function authorize(): bool
    {
        // Placeholder: For now, allow anyone.
        // Replace with actual authorization logic when auth is set up.
        // Example:
        // $user = $this->user(); // Gets the authenticated user via Sanctum
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
        // Based on your Restaurant model and potential DTOs
        return [
            'Name' => 'required|string|max:150|unique:Restaurants,Name', // Name should be unique in Restaurants table
            'ImageUrl' => 'nullable|string|max:1024|url', // Validate as a URL
            'AverageRating' => 'nullable|numeric|min:0|max:5', // Assuming a 0-5 rating scale
            'DeliveryTime' => 'nullable|string|max:50',
            'Distance' => 'nullable|string|max:50',
            'PriceRange' => 'nullable|string|max:50', // e.g., "$", "$$", "$$$"

            // For associating categories during creation
            'CategoryIds' => 'nullable|array', // Expect an array of category IDs
            'CategoryIds.*' => 'integer|exists:Categories,Id', // Each ID in the array must be an integer
                                                              // and exist in the 'Id' column of the 'Categories' table.
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