<?php
namespace App\Repositories\Core;

use App\Models\Role;
use App\Repositories\Core\BaseRepository;

class RoleRepository extends BaseRepository
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }


}