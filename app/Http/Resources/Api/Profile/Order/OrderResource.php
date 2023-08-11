<?php

namespace App\Http\Resources\Api\Profile\Order;

use App\Enums\OrderStatus;
use App\Http\Resources\Api\Category\SimpleCategoryResource;
use App\Http\Resources\Api\Event\VerySimpleEventResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $status = null;
        if ($this->status == OrderStatus::PURCHASED->value &&  $this->ticket?->event->end_reservation_date > now()) {
            $status = __('Valid for use');
        }

        if ($this->status == OrderStatus::PURCHASED->value &&  $this->ticket?->event->end_reservation_date < now()) {
            $status = __('Invalid for use');
        }

        if ($this->status == OrderStatus::REFUNDED->value) {
            $status = __('Refunded');
        }

        return [
            'id' => $this->id,
            'status' => $status,
            'event' => VerySimpleEventResource::make($this->ticket?->event),
            'quantity' => $this->quantity,
            'category' => SimpleCategoryResource::make($this->ticket?->event?->category),
            'price' => (float)$this->total_price,
            'date' => $this->ticket?->event?->date->format('Y-m-d'),
        ];
    }
}
