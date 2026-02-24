<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name', 'slug'];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($Role) {
            $Role->slug = Str::slug($Role->name);
        });
    }
}
