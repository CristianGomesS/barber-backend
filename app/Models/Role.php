<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name', 'slug'];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($role) {
            $role->slug = Str::slug($role->name, '_');
        });
    }
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function abilities()
    {
        return $this->belongsToMany(Ability::class);
    }
}
