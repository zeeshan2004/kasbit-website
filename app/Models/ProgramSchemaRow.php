<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramSchemaRow extends Model
{
    protected $fillable = [
        'program_schema_table_id',
        'semester',
        'subject',
        'credit_hours',
        'is_total',
        'sort_order',
    ];

    protected $casts = [
        'is_total' => 'boolean',
    ];

    public function table()
    {
        return $this->belongsTo(ProgramSchemaTable::class, 'program_schema_table_id');
    }
}
