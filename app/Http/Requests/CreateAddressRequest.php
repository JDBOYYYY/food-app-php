<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class CreateAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Will require authenticated user later
        return true; // Placeholder
    }

    public function rules(): array
    {
        return [
            'Street' => 'required|string|max:100',
            'Apartment' => 'nullable|string|max:50',
            'City' => 'required|string|max:50',
            'PostalCode' => 'required|string|max:20',
            'Country' => 'required|string|max:50',
            // UserId will be set from Auth::user()->id in the controller
        ];
    }
}