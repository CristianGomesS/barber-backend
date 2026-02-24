<?php
namespace App\Repositories\Core;

use App\Models\User;
use App\Repositories\Core\BaseRepository;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }


}