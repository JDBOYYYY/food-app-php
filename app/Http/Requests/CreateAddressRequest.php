<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class CreateAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Street' => 'required|string|max:100',
            'Apartment' => 'nullable|string|max:50',
            'City' => 'required|string|max:50',
            'PostalCode' => 'required|string|max:20',
            'Country' => 'required|string|max:50',
        ];
    }
}