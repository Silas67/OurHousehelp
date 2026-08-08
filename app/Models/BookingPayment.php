<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'user_id',
        'payment_for',
        'amount',
        'payment_method',
        'transaction_reference',
        'status',
        'payment_details',
        'bank_receipt',
        'whatsapp_receipt',
        'is_installment',
        'installment_type',
        'paid_at',
        'due_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'is_installment' => 'boolean',
        'paid_at' => 'datetime',
        'due_date' => 'date',
    ];

    // Relationships
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'pending_approval']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeForPurpose($query, string $purpose)
    {
        return $query->where('payment_for', $purpose);
    }

    public function scopeInstallments($query)
    {
        return $query->where('is_installment', true);
    }

    // Methods
    public function markAsCompleted($paymentDetails = null): void
    {
        $this->update([
            'status' => 'completed',
            'paid_at' => now(),
            'payment_details' => $paymentDetails ?? $this->payment_details,
        ]);
    }

    public function markAsFailed($paymentDetails = null): void
    {
        $this->update([
            'status' => 'failed',
            'payment_details' => $paymentDetails ?? $this->payment_details,
        ]);
    }

    public function markAsPendingApproval(): void
    {
        $this->update([
            'status' => 'pending_approval',
        ]);
    }

    public function isPaid(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return in_array($this->status, ['pending', 'pending_approval']);
    }
}
