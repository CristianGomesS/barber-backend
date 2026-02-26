<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use DateTimeInterface;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
    /**
     * Verifica se o usuário é um funcionário (barbeiro).
     *
     * @return bool
     */
    public function isEmployee(): bool
    {
        // Usamos opcional (?) para evitar erro caso a role não esteja carregada
        return $this->role?->slug === 'employee';
    }

    /**
     * Verifica se o usuário é um administrador.
     */
    public function isAdmin(): bool
    {
        return $this->role?->slug === 'admin';
    }
    /**
     * Apenas verifica a permissão (útil para IFs)
     */
    public function hasSchedulePermission(): bool
    {
        return $this->isEmployee() || $this->isAdmin();
    }

    /**
     * Valida a permissão e lança erro se falhar
     */
    public function validateSchedulePermission(): void
    {
        if (!$this->hasSchedulePermission()) {
            throw new \Exception("Usuário não tem permissão para possuir uma agenda.");
        }
    }
    public function validateProfessionalCapability(int $serviceId)
    {
        if (!$this->isEmployee() && !$this->isAdmin()) {
            throw new Exception("Não é um funcionario");
        }

        return $this->checkEmployeeService($serviceId);
    }
    public function checkEmployeeService(int $serviceId): Service
    {

        $service = $this->services()->where('services.id', $serviceId)->first();
        if (! $service) {
            throw new Exception("Este barbeiro não realiza o serviço selecionado.");
        }
        return $service;
    }
    //RELACIONAMENTOS
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_user')
            ->using(ServiceUser::class)
            ->withPivot('price')
            ->withTimestamps();
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
