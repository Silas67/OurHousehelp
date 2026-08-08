<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffShortlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'staff_id',
        'match_score',
        'match_reasons',
        'status',
        'client_visible',
        'notified_at',
        'responded_at',
        'shortlisted_at',
        'selected_at',
    ];

    protected $casts = [
        'match_reasons' => 'array',
        'client_visible' => 'boolean',
        'notified_at' => 'datetime',
        'responded_at' => 'datetime',
        'shortlisted_at' => 'datetime',
        'selected_at' => 'datetime',
    ];

    // Relationships
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    // Scopes
    public function scopeNotified($query)
    {
        return $query->where('status', 'notified');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeDeclined($query)
    {
        return $query->where('status', 'declined');
    }

    public function scopeShortlisted($query)
    {
        return $query->where('status', 'shortlisted');
    }

    public function scopeSelected($query)
    {
        return $query->where('status', 'selected');
    }

    public function scopeClientVisible($query)
    {
        return $query->where('client_visible', true);
    }

    public function scopeForStaff($query, $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    // Methods
    public function markAccepted(): void
    {
        $this->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);
    }

    public function markDeclined(): void
    {
        $this->update([
            'status' => 'declined',
            'responded_at' => now(),
        ]);
    }

    public function markShortlisted(): void
    {
        $this->update([
            'status' => 'shortlisted',
            'client_visible' => true,
            'shortlisted_at' => now(),
        ]);
    }

    public function markSelected(): void
    {
        $this->update([
            'status' => 'selected',
            'selected_at' => now(),
        ]);
    }
}
