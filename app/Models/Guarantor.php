<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guarantor extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'guarantor1_first_name',
        'guarantor1_last_name',
        'guarantor1_email',
        'guarantor1_phone',
        'guarantor1_relationship',
        'guarantor1_occupation',
        'guarantor1_address',
        'guarantor1_passport_path',
        'guarantor1_number',
        'guarantor1_nin'
    ];

    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }
}