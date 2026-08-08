<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'user_id',
        'status',
        'confirmed_at',
        'countdown_end'
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'countdown_end' => 'datetime',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}