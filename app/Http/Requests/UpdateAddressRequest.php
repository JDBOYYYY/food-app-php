<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Will require user to own the address or be admin
        return true; // Placeholder
    }

    public function rules(): array
    {
        return [
            'Street' => 'sometimes|required|string|max:100',
            'City' => 'sometimes|required|string|max:50',
            'PostalCode' => 'sometimes|required|string|max:20',
            'Country' => 'sometimes|required|string|max:50',
        ];
    }
}