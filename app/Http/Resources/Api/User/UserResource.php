<?php

namespace App\Http\Resources\Api\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'identity_number' => $this->identity_number,
            'hijri_date' => $this->hijri_date,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'token' => $this->when($this->token, $this->token),
        ];
    }
}
