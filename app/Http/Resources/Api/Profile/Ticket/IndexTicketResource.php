<?php

namespace App\Http\Resources\Api\Profile\Ticket;

use App\Http\Resources\Api\Event\SimpleEventResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $status = $this->event?->created_at > now();
        $status_translated = $status ? __('Valid for use') : __('Invalid for use');
        return [
            'id' => $this->id,
            'event' => $this->ticket?->event ? new SimpleEventResource($this->ticket->event) : null,
            'quantity' => (integer)$this->quantity,
            'price' => (integer)$this->quantity * (float)$this->price,
            'event_date' => Carbon::parse($this->event?->created_at)->format('Y-m-d'),
            'ticket_status' => $status_translated,
            'has_complaint' => $this->complaint ? true : false,
        ];
    }
}
