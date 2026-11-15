<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\HasDocumentNumber;

class MaintenanceTicket extends Model
{
    use HasFactory, HasDocumentNumber;

    protected $table = 'maintenance_tickets';

    protected $fillable = [
        'number',
        'machine_id',
        'priority',
        'status',
        'title',
        'description',
        'suggested_solution',
        'source',
        'opened_by',
        'assigned_to',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    public function documentPrefix(): string
    {
        return 'MT';
    }



    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }
    public function opener(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opened_by');
    }
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
