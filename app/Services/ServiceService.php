<?php

namespace App\Services;

use App\Repositories\Core\ServiceRepository;
use App\Repositories\Core\UserRepository;
use App\Services\BaseService;

/**
 * @property ServiceRepository $repository
 */
class ServiceService extends BaseService
{
public $userRepository;

    public function __construct(ServiceRepository $repository, UserRepository $userRepository)
    {
        parent::__construct($repository);
        $this->userRepository = $userRepository;
    }

    public function getServicesWithBarbers()
    {
        return $this->repository->getServicesWithBarbers();
    }
    /**
     * Vincula um barbeiro aos serviços com seus respectivos preços
     * * @param int $barberId
     * @param array $services // Ex: [ 1 => ['price' => 50.00], 2 => ['price' => 45.00] ]
     */
    public function syncBarberServices(int $barberId, array $services)
    {
        $barber = $this->userRepository->findById($barberId);
        if (!$barber->isEmployee() || !$barber->isAdmin()) {
            throw new \Exception("Apenas usuários com perfil de funcionário podem ser vinculados a serviços.");
        }
        return $barber->services()->sync($services);
    }
}
