<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'Street' => 'required|string|max:100',
            'City' => 'required|string|max:50',
            'PostalCode' => 'required|string|max:20',
            'Country' => 'required|string|max:50',
            // UserId will be set from Auth::user()->id in the controller
        ];
    }
}