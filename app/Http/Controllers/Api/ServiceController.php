<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Services\ServiceService;

class ServiceController extends BaseController
{
    public function __construct(ServiceService $service)
    {
        parent::__construct($service);
    }
}