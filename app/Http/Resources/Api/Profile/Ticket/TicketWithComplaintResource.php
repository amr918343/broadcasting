<?php

namespace App\Http\Resources\Api\Profile\Ticket;

use App\Http\Resources\Api\Event\SimpleEventResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketWithComplaintResource extends JsonResource
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
            'event' => $this->ticket?->event ? new SimpleEventResource($this->ticket->event) : null,
            'price' => (integer)$this->quantity * (float)$this->price,
            'event_date' => Carbon::parse($this->event?->created_at)->format('Y-m-d'),
        ];
    }
}
