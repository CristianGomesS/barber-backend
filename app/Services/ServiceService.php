<?php
namespace App\Services;

use App\Repositories\Core\ServiceRepository;
use App\Services\BaseService;

class ServiceService extends BaseService
{
    public function __construct(ServiceRepository $repository)
    {
        parent::__construct($repository);
    }
}