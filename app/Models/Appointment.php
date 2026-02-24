<?php

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'customer_id',
        'employee_id',
        'service_id',
        'scheduled_at',
        'final_price',
        'status'
    ];
    /**
     * Regra de Negócio (DDD): Método estático para criar um agendamento.
     * Em vez de preencher manualmente no Controller, usamos este método
     * que garante que todas as regras básicas foram seguidas.
     */
    public static function createWithPrice(
        int $customerId,
        int $employeeId,
        int $serviceId,
        float $price,
        string $scheduledAt
    ): self {
        // Exemplo de validação de domínio
        if ($price <= 0) {
            throw new DomainException("Um agendamento não pode ter preço zero ou negativo.");
        }

        return new self([
            'customer_id' => $customerId,
            'employee_id' => $employeeId,
            'service_id' => $serviceId,
            'final_price' => $price,
            'scheduled_at' => $scheduledAt,
            'status' => 'pending' // Todo agendamento nasce pendente
        ]);
    }
}
