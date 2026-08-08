<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OneTimeService extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_size', 'price_min', 'price_max', 'details'
    ];
}
