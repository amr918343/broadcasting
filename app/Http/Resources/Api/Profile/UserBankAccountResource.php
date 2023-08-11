<?php

namespace App\Http\Resources\Api\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserBankAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'iban' => $this->iban,
            'bank_name' => $this->bank_name,
            'swift_code' => $this->swift_code,
        ];
    }
}
