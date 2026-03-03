<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAvailability extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'day_of_week',
        'start_time',
        'end_time',
        'break_start',
        'break_end',
        'is_active'
    ];

    // Fazemos o cast para 'string' ou deixamos o Carbon lidar via Service
    protected $casts = [
        'is_active' => 'boolean',
        'day_of_week' => 'integer'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
