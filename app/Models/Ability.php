<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ability extends Model
{
    use HasFactory;
    protected $table = 'abilities';

    protected $fillable = [
        'name',
        'slug'
    ];

    protected $hidden = [
        'updated_at'
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($ability) {
            $ability->slug = Str::slug($ability->name,'_');
        });
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
