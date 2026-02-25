<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleAbility extends Model
{
    protected $table = 'ability_role';

    protected $fillable = [
        'role_id',
        'ability_id',
    ];
}
