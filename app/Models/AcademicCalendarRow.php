<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicCalendarRow extends Model
{
    protected $fillable = [
        'academic_calendar_table_id',
        'occasion',
        'days',
        'date_text',
        'sort_order',
    ];

    public function table()
    {
        return $this->belongsTo(AcademicCalendarTable::class, 'academic_calendar_table_id');
    }
}
