<?php

namespace App\Http\Resources\Api\Profile\Wallet;

use App\Http\Resources\Api\Event\VerySimpleEventResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexWalletTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $event = $this->walletable?->event ? $this->walletable?->event : $this->walletable?->ticket?->event;
        return [
            'id' => (int)$this->id,
            'event' => $this->walletable?->event ? VerySimpleEventResource::make($this->walletable?->event) : VerySimpleEventResource::make($this->walletable?->ticket?->event),
            'quantity' => (int)$this->walletable?->quantity,
            'subtotal' => (float)$this->walletable?->subtotal ? (float)$this->walletable?->subtotal : (float)$this->walletable?->price,
            'total' => (float)$this->walletable?->total_price ? (float)$this->walletable?->total_price : (float)$this->walletable?->price,
            'type' => __($this->type),
        ];
    }
}
