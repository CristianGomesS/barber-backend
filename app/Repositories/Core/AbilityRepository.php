<?php
namespace App\Repositories\Core;

use App\Models\Ability;
use App\Repositories\Core\BaseRepository;

class AbilityRepository extends BaseRepository
{
    public function __construct(Ability $model)
    {
        parent::__construct($model);
    }


}