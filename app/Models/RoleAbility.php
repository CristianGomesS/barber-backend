<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class RoleAbility extends Model
{
    protected $table = 'ability_role';

    protected $fillable = [
        'role_id',
        'ability_id',
    ];
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
