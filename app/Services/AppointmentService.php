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
    /**
     * Cria um agendamento para um funcionário com base nos dados enviados.
     * 
     * @param array $data Dados para criar o agendamento.
     * @return mixed O agendamento criado.
     * 
     * @throws Exception Se o funcionário tiver algum agendamento no horário especificado ou se a data for retroativa.
     */
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
     * Verifica se o funcionário tem algum agendamento no horário especificado.
     *
     * @param int $employeeId O ID do funcionário.
     * @param Carbon $start O horário de início do agendamento.
     * @param Carbon $end O horário de fim do agendamento.
     *
     * @throws Exception Se o funcionário tiver algum agendamento no horário especificado.
     */
    public function checkAvailable(int $employeeId, $start, $end)
    {
        $conflict = $this->repository->checkAvailable($employeeId, $start, $end);
        if ($conflict) {
            throw new Exception("O barbeiro já possui um agendamento neste horário.");
        }
    }

    /**
     * Retorna todas as consultas do usuário autenticado.
     *
     * @param int $userId O ID do usuário para o qual as consultas serão obtidas.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function myAppointments(int $userId)
    {
        return $this->repository->myAppointments($userId);
    }
    /**
     * Retorna a agenda diária do funcionário específico.
     * @param int $employeeId O ID do funcionário para o qual a agenda diária será obtida.
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \Exception Se o usuário não tiver permissão para visualizar a agenda do funcionário.
     */
    public function getEmployeeDailyAgenda(int $employeeId, ?string $date = null)
    {
        $targetDate = $date
            ? \Illuminate\Support\Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d')
            : now()->format('Y-m-d');
        $user = $this->userRepository->findById($employeeId);
        $user->validateSchedulePermission();
        return $this->repository->getDailySchedule($employeeId, $targetDate);
    }

    /**
     * Finaliza o agendamento mudando seu status para "completed".
     *
     * @param int $appointmentId
     * @param int $userId O ID do usuário que está tentando finalizar (normalmente o funcionário).
     * @return mixed
     * @throws Exception
     */
    public function finalize(int $appointmentId, int $userId)
    {
        $appointment = $this->repository->findById($appointmentId);
        $user = $this->userRepository->findById($userId);

        // Apenas o funcionário que vai realizar o serviço ou um admin pode finalizar
        if ($appointment->employee_id !== $userId && !$user->isAdmin()) {
            throw new Exception("Você não tem permissão para finalizar este atendimento.");
        }
        if ($appointment->status === 'completed') {
            throw new Exception("Este atendimento já foi finalizado.");
        }
        return DB::transaction(function () use ($appointmentId, $appointment) {
            $appointment->delete();
            return $this->repository->update($appointmentId, ['status' => 'completed']);
        });
    }
}
