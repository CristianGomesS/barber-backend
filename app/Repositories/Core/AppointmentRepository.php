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

    /**
     * Verifica se o funcionário tem alguma consulta agendada no intervalo de tempo especificado.
     *
     * @param int $employeeId O ID do funcionário.
     * @param string $start A data e hora de início do intervalo de tempo.
     * @param string $end A data e hora de fim do intervalo de tempo.
     *
     * @return bool
     */
    public function checkAvailable($employeeId, $start, $end)
    {
        return $this->entity->where('employee_id', $employeeId)
            ->where('status', '!=', 'canceled')
            ->where(function ($query) use ($start, $end) {
                $query->where('scheduled_at', '<', $end)
                    ->where('end_at', '>', $start);
            })
            ->exists();
    }

/**
 * Retorna as consultas do usuário autenticado.
 *
 * @param int $userId O ID do usuário autenticado.
 *
 * @return \Illuminate\Database\Eloquent\Collection
 */
    public function myAppointments(int $userId)
    {
        return $this->entity->where('customer_id', $userId)->with(['service', 'employee'])
            ->OrderBy('scheduled_at', 'desc')
            ->get();
   }

/**
 * Retorna a agenda diária do funcionário específico.
 *
 * @param int $employeeId O ID do funcionário.
 * @param string $date A data na qual a agenda diária será obtida (opcional).
 *
 * @return \Illuminate\Database\Eloquent\Collection
 */
    public function getDailySchedule(int $employeeId, string $date = null)
    {
        $targetDate = $date ?: now()->format('Y-m-d');

        return $this->entity
            ->with([
            'customer:id,name,email', 
            'service:id,name,duration_minutes',
            ])
            ->where('employee_id', $employeeId)
            ->whereDate('scheduled_at', $targetDate)
            ->where('status', '!=', 'canceled')
            ->orderBy('scheduled_at', 'asc')
            ->get();
    }
}
