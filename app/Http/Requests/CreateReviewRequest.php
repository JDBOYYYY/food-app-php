<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        // ProductId will likely come from the route (e.g., POST /api/products/{product}/reviews)
        // UserId will come from Auth::user()
        return [
            'Rating' => 'required|integer|min:1|max:5',
            'Comment' => 'nullable|string|max:2000', // Max length for comment
            // 'ProductId' => 'required|integer|exists:Products,Id', // If passed in body
        ];
    }
}