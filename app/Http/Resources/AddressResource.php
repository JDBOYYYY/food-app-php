<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'Id' => $this->Id,
            'Street' => $this->Street,
            'Apartment' => $this->Apartment,
            'City' => $this->City,
            'PostalCode' => $this->PostalCode,
            'Country' => $this->Country,
            'UserId' => $this->UserId,
            'user_name' => $this->whenLoaded('user', fn() => $this->user->name),
        ];
    }
}
