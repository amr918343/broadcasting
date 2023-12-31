<?php

namespace App\Http\Resources\Api\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'image' => $this->image ? asset('storage/' . $this->image) : asset('assets/user/logo.jpg'),
            'full_name' => $this->full_name,
            'wallet' => (float)$this->wallet,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'bank_account' => UserBankAccountResource::make($this->bankAccount),
        ];
    }
}
