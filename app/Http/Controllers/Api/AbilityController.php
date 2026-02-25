<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Services\AbilityService;

class AbilityController extends BaseController
{
    public function __construct(AbilityService $service)
    {
        parent::__construct($service);
    }
}