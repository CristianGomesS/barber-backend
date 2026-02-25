<?php

namespace App\Repositories\Core;

use App\Models\Appointment;
use App\Repositories\Core\BaseRepository;

class AppointmentRepository extends BaseRepository
{
    public function __construct(Appointment $entity)
    {
        parent::__construct($entity);
    }

    public function checkAvailable($employeeId, $start, $end)
    {
        return $this->entity->where('employee_id', $employeeId)
            ->where('status', '!=', 'canceled')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('scheduled_at', [$start, $end->subSeconds()])
                    ->orWhere('sheduled_at', $start);
            })->exists();
    }
    public function myAppointments(int $userId)
    {
        return $this->entity->where('customer_id', $userId)->with(['service', 'employee'])
            ->OrderBy('scheduled_at', 'desc')
            ->get();
    }
}
