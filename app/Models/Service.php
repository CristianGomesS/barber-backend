<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * Atributos que podem ser preenchidos via Request (Segurança).
     */
    protected $fillable = [
        'name',
        'duration_minutes',
        'slug',
    ];

    protected static function boot(){
        parent::boot();
        static::creating(function(Service $service){
            $service->slug= Str::slug($service->name);
        });
    }
    /**
     * Relacionamento: Um serviço pode ser prestado por vários barbeiros (User).
     * Aqui buscamos o preço que está na tabela pivot 'service_user'.
     */
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('price')
            ->withTimestamps();
    }
}
