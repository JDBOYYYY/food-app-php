<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // this->resource refers to the Product model instance
        return [
            'Id' => $this->Id, // Assuming your Product model uses 'Id' as primaryKey
            'Name' => $this->Name,
            'Description' => $this->Description,
            'Price' => (float) $this->Price, // Cast to float
            'ImageUrl' => $this->ImageUrl,
            'RestaurantId' => $this->RestaurantId,
            'CategoryId' => $this->CategoryId,

            // Include related Category information (belongsTo relationship)
            // This will only be included if 'category' relationship was eager-loaded
            'Category' => new CategoryResource($this->whenLoaded('category')),
            // Or, if you just want the name:
            // 'CategoryName' => $this->whenLoaded('category', function () {
            //     return $this->category->Name;
            // }),


            // Include related Restaurant information (belongsTo relationship)
            // This will only be included if 'restaurant' relationship was eager-loaded
            'Restaurant' => new RestaurantResource($this->whenLoaded('restaurant')),
            // Or, if you just want the name:
            // 'RestaurantName' => $this->whenLoaded('restaurant', function () {
            //     return $this->restaurant->Name;
            // }),


            // If you want to include timestamps from the Product model:
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}