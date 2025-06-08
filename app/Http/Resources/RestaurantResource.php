<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'Id' => $this->Id,
            'Name' => $this->Name,
            'ImageUrl' => $this->ImageUrl,
            'AverageRating' => (float) $this->AverageRating,
            'DeliveryTime' => $this->DeliveryTime,
            'Distance' => $this->Distance,
            'PriceRange' => $this->PriceRange,
            'Categories' => CategoryResource::collection(
                $this->whenLoaded('categories')
            ),
            'Products' => ProductResource::collection(
                $this->whenLoaded('products')
            ),
        ];
    }
}