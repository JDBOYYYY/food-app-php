<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'Id' => $this->Id,
            'UserId' => $this->UserId,
            'UserName' => $this->whenLoaded('user', fn() => $this->user->name),
            'ProductId' => $this->ProductId,
            'ProductName' => $this->whenLoaded('product', fn() => $this->product->Name),
            'Rating' => $this->Rating,
            'Comment' => $this->Comment,
            'ReviewDate' => $this->ReviewDate->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}