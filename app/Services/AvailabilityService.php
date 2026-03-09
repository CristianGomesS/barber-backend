<?php

namespace App\Services;

use App\Repositories\Core\AppointmentRepository;
use App\Repositories\Core\AvailabilityRepository;
use App\Repositories\Core\ServiceRepository;
use App\Repositories\Core\UserRepository;
use App\Services\BaseService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * @property AvailabilityRepository $repository
 */

class AvailabilityService extends BaseService
{
    public $userRepopository;
    public $appointmentRepository;
    public $serviceRepository;

    public function __construct(AvailabilityRepository $repository, UserRepository $userRepopository, AppointmentRepository $appointmentRepository, ServiceRepository $serviceRepository)
    {
        parent::__construct($repository);
        $this->userRepopository = $userRepopository;
        $this->appointmentRepository = $appointmentRepository;
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Obtém os horários disponíveis para um funcionário específico em um determinado dia e serviço.
     *
     * @param int $employeeId O ID do funcionário para o qual os horários serão obtidos.
     * @param string $date A data na qual os horários serão obtidos.
     * @param int $serviceId O ID do serviço para o qual os horários serão obtidos.
     *
     * @return array Uma lista de strings representando os horários disponíveis no formato 'H:i'.
     */
    public function getAvailableSlots(int $employeeId, string $date, int $serviceId): array
    {
        $service = $this->serviceRepository->findById($serviceId);
        $duration = $service->duration_minutes;

        $carbonDate = Carbon::parse($date);

        $availability = $this->repository->findByEmployeeAndDay($employeeId, $carbonDate->dayOfWeek);
        if (!$availability) {
            return [];
        }
        $booked = $this->appointmentRepository->getDailySchedule($employeeId, $date);

        // logica do almoço
        if ($availability->break_start && $availability->break_end) {
            // criando um objeto "fantasma" que simula um agendamento
            $lunchBreak = (object) [
                'scheduled_at' => Carbon::parse($date . ' ' . $availability->break_start),
                'end_at' => Carbon::parse($date . ' ' . $availability->break_end)
            ];

            // Empurramos esse bloqueio para dentro da lista de ocupados
            $booked->push($lunchBreak);
        }
        // ----------------------------

        $slots = [];
        $start = Carbon::parse($date . ' ' . $availability->start_time);
        $end = Carbon::parse($date . ' ' . $availability->end_time);

        while ($start->copy()->addMinutes($duration) <= $end) {
            $slotEnd = $start->copy()->addMinutes($duration);

            $isOccupied = $booked->contains(function ($appointment) use ($start, $slotEnd) {
                return $appointment->scheduled_at < $slotEnd && $appointment->end_at > $start;
            });

            if (!$isOccupied && $start->isFuture()) {
                $slots[] = $start->format('H:i');
            }

            $start->addMinutes(15);
        }

        return $slots;
    }
    /**
     * Armazena a disponibilidade de um funcionário em massa.
     * @param array $availabilities
     * @return mixed
     * @throws \Exception
     */
    public function storeBulkAvailability(array $availabilities)
    {
        /** @var \App\Models\User $user */
        $user = $this->userRepopository->findById($availabilities['user_id']);
        $user->validateActionPermission();

        return DB::transaction(function () use ($user, $availabilities) {
            foreach ($availabilities['availabilities'] as $item) {
                if (strtotime($item['start_time']) >= strtotime($item['end_time'])) {
                    throw new \Exception("Erro no dia {$item['day_of_week']}: Início deve ser menor que o fim.");
                }
                $item['user_id'] = $user->id;
                $this->repository->updateOrCreate($item);
            }
        });
    }
}
