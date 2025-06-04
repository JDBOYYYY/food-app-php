<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // this->resource refers to the Order model instance
        return [
            'Id' => $this->Id,
            'UserId' => $this->UserId,
            // Include User's name if the 'user' relationship is loaded
            'UserName' => $this->whenLoaded('user', function () {
                return $this->user->name; // Assuming 'name' attribute on User model
            }),
            'OrderDate' => $this->OrderDate ? $this->OrderDate->toIso8601String() : null,
            'TotalAmount' => (float) $this->TotalAmount,
            'Status' => $this->Status,

            'ShippingAddressId' => $this->ShippingAddressId,
            // Include full ShippingAddress object if 'shippingAddress' relationship is loaded
            'ShippingAddress' => new AddressResource($this->whenLoaded('shippingAddress')),

            'BillingAddressId' => $this->BillingAddressId,
            // Include full BillingAddress object if 'billingAddress' relationship is loaded
            'BillingAddress' => new AddressResource($this->whenLoaded('billingAddress')),

            // Include OrderItems collection if 'orderItems' relationship is loaded
            // Each item in the collection will be transformed by OrderItemResource
            'OrderItems' => OrderItemResource::collection($this->whenLoaded('orderItems')),

            // Include Payment information if 'payment' relationship is loaded
            'Payment' => new PaymentResource($this->whenLoaded('payment')),

            // Timestamps for the order itself
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : null,
        ];
    }
}