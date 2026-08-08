<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_type',
        'plan_name', 
        'cost',
        'about',
        'duration',
        'num_searches'
    ];

    protected $casts = [
        'cost' => 'float',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}