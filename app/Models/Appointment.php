<?php

namespace App\Models;

use DateTimeInterface;
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
        'end_at',
        'final_price',
        'status'
    ];
    protected $casts = [
        'scheduled_at' => 'datetime',
        'end_at' => 'datetime',
    ];
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
