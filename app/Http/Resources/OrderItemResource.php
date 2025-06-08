<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
public function toArray(Request $request): array
    {
        // Be explicit to ensure all needed data is included
        return [
            'Id' => $this->Id,
            'OrderId' => $this->OrderId,
            'ProductId' => $this->ProductId,
            'Quantity' => $this->Quantity,
            'UnitPrice' => $this->UnitPrice,
            // Use the ProductResource to ensure nested relationships like the restaurant are included
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
