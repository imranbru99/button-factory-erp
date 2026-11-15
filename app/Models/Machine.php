<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\Auditable;

class Machine extends Model
{
    use HasFactory, Auditable;

    protected $table = 'machines';

    protected $fillable = [
        'code',
        'name',
        'machine_type',
        'work_center_id',
        'department_id',
        'status',
        'capacity_per_hour',
        'hourly_rate',
        'commissioned_on',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'capacity_per_hour' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
            'commissioned_on' => 'date',
        ];
    }



    public function plans(): HasMany
    {
        return $this->hasMany(MaintenancePlan::class, 'machine_id');
    }
    public function tickets(): HasMany
    {
        return $this->hasMany(MaintenanceTicket::class, 'machine_id');
    }
    public function runtimes(): HasMany
    {
        return $this->hasMany(MachineRuntime::class, 'machine_id');
    }
    public function workCenter(): BelongsTo
    {
        return $this->belongsTo(WorkCenter::class, 'work_center_id');
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
