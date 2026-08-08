<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'househelp_id',
        'payment_id',
        'click_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function househelp()
    {
        return $this->belongsTo(User::class, 'househelp_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
