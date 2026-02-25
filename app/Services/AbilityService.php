<?php
namespace App\Services;

use App\Repositories\Core\AbilityRepository;
use App\Services\BaseService;

class AbilityService extends BaseService
{
    public function __construct(AbilityRepository $repository)
    {
        parent::__construct($repository);
    }
}