<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_request_id',
        'amount',
        'payment_method',
        'transaction_reference',
        'status',
        'payment_details',
        'bank_receipt',
        'paid_at',
        'expires_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'payment_details' => 'array'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    // Status checks
    public function isActive()
    {
        return $this->status === 'completed' && $this->expires_at && $this->expires_at->isFuture();
    }

    public function isExpired()
    {
        return $this->status === 'completed' && $this->expires_at && $this->expires_at->isPast();
    }

    public function isPending()
    {
        return in_array($this->status, ['pending', 'pending_approval']);
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}