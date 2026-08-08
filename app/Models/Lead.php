<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'source',
        'status',
        'notes',
        'follow_up_date',
    ];

    protected $casts = [
        'follow_up_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope for incomplete registrations
    public function scopeIncompleteRegistrations($query)
    {
        return $query->where('status', 'registered_incomplete');
    }

    // Scope for follow-up needed
    public function scopeNeedsFollowUp($query)
    {
        return $query->where('follow_up_date', '<=', now());
    }
}