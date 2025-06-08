<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Street' => 'sometimes|required|string|max:100',
            'Apartment' => 'sometimes|nullable|string|max:50',
            'City' => 'sometimes|required|string|max:50',
            'PostalCode' => 'sometimes|required|string|max:20',
            'Country' => 'sometimes|required|string|max:50',
        ];
    }
}