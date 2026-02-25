<?php

namespace App\Services;

use App\Repositories\Core\AppointmentRepository;
use App\Repositories\Core\UserRepository;
use App\Services\BaseService;
use Carbon\Carbon;
use Exception;

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


    public function createAppointment(array $data)
    {
        $user = $this->userRepository->findById($data['employee_id']);
        $service = $user->services()->where('id', $data['service_id'])->first();
        if (! $service) {
            throw new Exception("Este barbeiro não realiza o serviço selecionado.");
        }
        $start = Carbon::parse($data['schaduled_at']);
        $end = Carbon::parse($data['schaduled_at'])->addMinutes($service->duration_minutes);
        $this->checkAvailable($data['employee_id'], $start, $end);
        $data['final_price'] = $service->pivot->price;
        $data['status'] = 'pending';
        return $this->repository->store($data);
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
}
