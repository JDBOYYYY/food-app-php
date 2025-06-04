<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // this->resource refers to the Restaurant model instance
        return [
            'Id' => $this->Id, // Assuming your Restaurant model uses 'Id' as primaryKey
            'Name' => $this->Name,
            'ImageUrl' => $this->ImageUrl,
            'AverageRating' => (float) $this->AverageRating, // Cast to float for consistency
            'DeliveryTime' => $this->DeliveryTime,
            'Distance' => $this->Distance,
            'PriceRange' => $this->PriceRange,

            // To include related categories (many-to-many relationship)
            // This will only be included if 'categories' relationship was eager-loaded
            // e.g., Restaurant::with('categories')->find(1);
            'Categories' => CategoryResource::collection($this->whenLoaded('categories')),

            // If you want to include timestamps from the Restaurant model:
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}