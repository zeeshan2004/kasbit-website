<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'query_code',
    'user_id',
    'name',
    'email',
    'department_id',
    'message',
    'status',
    'admin_notes',
    'resolved_at',
])]
class Query extends Model
{
    public const STATUSES = [
        'pending',
        'in_progress',
        'resolved',
        'closed',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'in_progress' => 'In Progress',
            default => str($this->status)->headline()->toString(),
        };
    }
}
