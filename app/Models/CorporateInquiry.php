<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_name',
        'work_email',
        'phone',
        'staff_count',
        'staff_types',
        'preferred_start_date',
        'notes',
        'admin_notes',
        'status',
    ];

    protected $casts = [
        'staff_types' => 'array',
        'preferred_start_date' => 'date',
    ];
}