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
            'scheduled_at' => $this->scheduled_at->format('d/m/Y H:i'),
            'status' => $this->status,
            'price' => (float) $this->final_price,
            'service' => [
                'name' => $this->service->name,
            ],
            'barber' => [
                'name' => $this->employee->name,
            ],
        ];
    }
}
