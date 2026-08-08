<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    // Specify the fillable attributes
    protected $fillable = [
        'name',
        'cost',
        'percentage',
        'description',
        'point1',
        'point2',
        'point3',
        'days_per_week',
        'working_hours_start_1',
        'working_hours_end_1',
        'working_hours_start_2',
        'working_hours_end_2',
        'working_hours_start_3',
        'working_hours_end_3',
        'working_hours_start_4',
        'working_hours_end_4',
        'working_hours_start_5',
        'working_hours_end_5',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
