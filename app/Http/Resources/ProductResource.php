<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'Id' => $this->Id,
            'Name' => $this->Name,
            'Description' => $this->Description,
            'Price' => (float) $this->Price,
            'ImageUrl' => $this->ImageUrl,
            'RestaurantId' => $this->RestaurantId,
            'CategoryId' => $this->CategoryId,
            'Category' => new CategoryResource($this->whenLoaded('category')),
            'Restaurant' => new RestaurantResource(
                $this->whenLoaded('restaurant')
            ),
        ];
    }
}