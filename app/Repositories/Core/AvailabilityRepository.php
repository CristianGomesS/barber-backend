<?php

namespace App\Repositories\Core;

use App\Models\EmployeeAvailability;
use App\Repositories\Core\BaseRepository;

class AvailabilityRepository extends BaseRepository
{

    public function __construct(EmployeeAvailability $entity)
    {
        parent::__construct($entity);
    }

    /**
     * Busca um registro de disponibilidade de funcionário com base no ID do funcionário e no dia da semana.
     *
     * @param int $userId O ID do funcionário.
     * @param int $dayOfWeek O dia da semana (0 = domingo, 1 = segunda, ..., 6 = sábado).
     *
     * @return \App\Models\EmployeeAvailability|null
     */
    public function findByEmployeeAndDay(int $userId, int $dayOfWeek)
    {
        return $this->entity
            ->where('user_id', $userId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();
    }
    /**
     * Atualiza ou cria um registro de disponibilidade de funcionário.
     *
     * @param array $data Dados para atualizar ou criar o registro.
     * @return EmployeeAvailability O registro atualizado ou criado.
     */
    public function updateOrCreate(array $data)
    {
        return $this->entity->updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'day_of_week' => $data['day_of_week']
            ],
            [
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'break_start' => $data['break_start'] ?? null, 
                'break_end' => $data['break_end'] ?? null,     
                'is_active' => $data['is_active'] ?? true
            ]
        );
    }
}
