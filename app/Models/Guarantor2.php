<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guarantor2 extends Model
{
    use HasFactory;


    protected $fillable = [
        'applicant_id',
        'guarantor2_first_name',
        'guarantor2_last_name',
        'guarantor2_email',
        'guarantor2_phone',
        'guarantor2_nin',
        'guarantor2_relationship',
        'guarantor2_occupation',
        'guarantor2_address',
        'guarantor2_passport_path',
        'guarantor2_number'
    ];


    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }
}