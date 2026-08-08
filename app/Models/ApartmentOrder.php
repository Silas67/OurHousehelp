<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApartmentOrder extends Model
{
    use HasFactory;

    protected $table = 'apartment_orders';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'state_of_residence',
        'address',
        'landmark',
        'apartment_type',
        'service_type',
        'price',
    ];
}
