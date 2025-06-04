<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'Id' => $this->Id,
            'OrderId' => $this->OrderId,
            'PaymentDate' => $this->PaymentDate->toIso8601String(),
            'Amount' => (float) $this->Amount,
            'PaymentMethod' => $this->PaymentMethod,
            'TransactionId' => $this->TransactionId,
            'Status' => $this->Status,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}