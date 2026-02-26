<?php

namespace App\Services;

use App\Repositories\Core\AppointmentRepository;
use App\Repositories\Core\UserRepository;
use App\Services\BaseService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * @property AppointmentRepository $repository
 */
class AppointmentService extends BaseService
{
    public $userRepository;
    public function __construct(AppointmentRepository $repository, UserRepository $userRepository)
    {
        parent::__construct($repository);
        $this->userRepository = $userRepository;
    }


    public function createAppointment(array $data): mixed
    {
        $employee = $this->userRepository->findById($data['employee_id']);
        $service = $employee->validateProfessionalCapability($data['service_id']);
        if (Carbon::parse($data['scheduled_at'])->isPast()) {
            throw new Exception("Não é possível agendar em uma data retroativa.");
        }

        $start = Carbon::parse($data['scheduled_at']);
        $end = $start->copy()->addMinutes($service->duration_minutes);
        $this->checkAvailable($data['employee_id'], $start, $end);
        $data['final_price'] = $service->pivot->price;
        $data['status'] = 'pending';
        $data['end_at'] = $end;
        return DB::transaction(function () use ($data) {
            return $this->repository->store($data);
        });
    }

    /**
     * Verifica se o barbeiro tem algum agendamento que sobrepõe o horário desejado
     */
    public function checkAvailable(int $employeeId, $start, $end)
    {
        $conflict = $this->repository->checkAvailable($employeeId, $start, $end);
        if ($conflict) {
            throw new Exception("O barbeiro já possui um agendamento neste horário.");
        }
    }

    public function myAppointments(int $userId)
    {
        return $this->repository->myAppointments($userId);
    }
    public function getEmployeeDailyAgenda(int $employeeId)
    {
        $user = $this->userRepository->findById($employeeId);
        $user->validateSchedulePermission();
        return $this->repository->getDailySchedule($employeeId);
    }
}
