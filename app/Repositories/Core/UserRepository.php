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

    public function findWhereFirst(string $column, string $value)
    {
        return $this->entity->where($column, $value)->with(['role.abilities'])->first();
    }
    public function findById(int $id)
    {
         return $this->entity->findOrFail($id)->with(['role.abilities']);
    }
}