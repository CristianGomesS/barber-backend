<?php
namespace App\Repositories\Core;

use App\Models\Service;
use App\Repositories\Core\BaseRepository;

class ServiceRepository extends BaseRepository
{
    public function __construct(Service $model)
    {
        parent::__construct($model);
    }


}