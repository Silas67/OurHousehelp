<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveOutPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'amount',
        'payment_method',
        'transaction_reference',
        'status',
        'payment_details',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'paid_at' => 'datetime'
    ];

    // Relationships
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Methods
    public function markAsCompleted($paymentDetails = null)
    {
        $this->update([
            'status' => 'completed',
            'paid_at' => now(),
            'payment_details' => $paymentDetails ?? $this->payment_details
        ]);
    }

    public function markAsFailed($paymentDetails = null)
    {
        $this->update([
            'status' => 'failed',
            'payment_details' => $paymentDetails ?? $this->payment_details
        ]);
    }

    public function isPaid(): bool
    {
        return $this->status === 'completed';
    }
}