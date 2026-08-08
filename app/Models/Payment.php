<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_plan_id',
        'plan_type',
        'payment_frequency',
        'amount',
        'status',
        'about_payment',
        'payment_method',
        'num_searches',
        'used_searches',
        'receipt_path',
        'expiry_date'
    ];

    protected $casts = [
        'amount' => 'float',
        'expiry_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentRecords()
    {
        return $this->hasMany(PaymentRecord::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }


    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function paymentPlan()
    {
        return $this->belongsTo(PaymentPlan::class, 'payment_plan_id');
    }


    public function plan()
    {
        return $this->belongsTo(PaymentPlan::class, 'payment_plan_id');
    }

    public function isExpired()
    {
        $today = Carbon::now();
        return $this->expiry_date < $today || $this->num_searches <= 0;
    }

    public function requestClick()
    {
        return $this->hasMany(RequestClick::class, 'payment_id');
    }


    public function isExhausted()
    {
        return $this->status == 'approved' && $this->num_searches <= $this->requestClick()->count();
    }
}
