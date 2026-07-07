<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramSchemaRow extends Model
{
    protected $fillable = [
        'program_schema_table_id',
        'semester',
        'image_path',
        'subject',
        'credit_hours',
        'col3_text',
        'col4_text',
        'col5_text',
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
