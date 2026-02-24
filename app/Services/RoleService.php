<?php
namespace App\Services;

use App\Repositories\Core\RoleRepository;
use App\Services\BaseService;

class RoleService extends BaseService
{
    public function __construct(RoleRepository $repository)
    {
        parent::__construct($repository);
    }
}