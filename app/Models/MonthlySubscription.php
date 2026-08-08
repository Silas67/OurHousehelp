<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlySubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'user_id',
        'plan_type',
        'monthly_amount',
        'status',
        'next_billing_date',
        'started_at',
        'cancelled_at',
    ];

    protected $casts = [
        'monthly_amount' => 'decimal:2',
        'next_billing_date' => 'date',
        'started_at' => 'date',
        'cancelled_at' => 'date',
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
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeDueBilling($query)
    {
        return $query->where('status', 'active')
            ->where('next_billing_date', '<=', now()->toDateString());
    }

    // Methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function cancel(): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }

    public function pause(): void
    {
        $this->update([
            'status' => 'paused',
        ]);
    }

    public function resume(): void
    {
        $this->update([
            'status' => 'active',
        ]);
    }
}
