<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'status' => $this->status,
            'price' => (float) $this->final_price,
            'scheduled_at' => $this->scheduled_at->format('d/m/Y H:i'),
            'end_at' => $this->end_at->format('d/m/Y H:i'),
            'starts_in' => $this->scheduled_at->diffForHumans(), // Ex: "em 20 minutos" ou "há 1 hora"
            'service' => [
                'name' => $this->service->name,
                'duration_minutes' => $this->service->duration_minutes,
            ],
            'barber' => [
                'name' => $this->employee->name,
            ],
            'customer' => [
                'name' => $this->customer->name,
            ],
        ];
    }
}
