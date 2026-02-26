<?php

namespace App\Models;

use DateTimeInterface;
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

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function (Service $service) {
            $service->slug = Str::slug($service->name, '_');
        });
    }
    /**
     * Relacionamento: Um serviço pode ser prestado por vários barbeiros (User).
     * Aqui buscamos o preço que está na tabela pivot 'service_user'.
     */
    public function barbers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'service_user')
            ->using(ServiceUser::class)
            ->withPivot('price')
            ->withTimestamps();
    }
}
