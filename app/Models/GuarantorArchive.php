<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuarantorArchive extends Model
{
    use HasFactory;

    protected $table = 'guarantor_archives';

    protected $fillable = [
        'applicant_id',
        'guarantor_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'relationship',
        'occupation',
        'address',
        'passport_path',
        'signature_path',
    ];
}
