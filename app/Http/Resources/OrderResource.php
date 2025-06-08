<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'Id' => $this->Id,
            'UserId' => $this->UserId,
            'UserName' => $this->whenLoaded('user', function () {
                return $this->user->name;
            }),
            'OrderDate' => $this->OrderDate
                ? $this->OrderDate->toIso8601String()
                : null,
            'TotalAmount' => (float) $this->TotalAmount,
            'Status' => $this->Status,
            'ShippingAddressId' => $this->ShippingAddressId,
            'ShippingAddress' => new AddressResource(
                $this->whenLoaded('shippingAddress')
            ),
            'BillingAddressId' => $this->BillingAddressId,
            'BillingAddress' => new AddressResource(
                $this->whenLoaded('billingAddress')
            ),
            'OrderItems' => OrderItemResource::collection(
                $this->whenLoaded('orderItems')
            ),
            'Payment' => new PaymentResource($this->whenLoaded('payment')),
            'created_at' => $this->created_at
                ? $this->created_at->toIso8601String()
                : null,
            'updated_at' => $this->updated_at
                ? $this->updated_at->toIso8601String()
                : null,
        ];
    }
}