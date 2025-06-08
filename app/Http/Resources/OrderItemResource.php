<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
public function toArray(Request $request): array
    {
        return [
            'Id' => $this->Id,
            'OrderId' => $this->OrderId,
            'ProductId' => $this->ProductId,
            'Quantity' => $this->Quantity,
            'UnitPrice' => $this->UnitPrice,
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
