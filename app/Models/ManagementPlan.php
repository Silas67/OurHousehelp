<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'label',
        'base_price',
        'max_price',
        'features',
        'free_replacements',
        'replacement_window',
        'has_supervision',
        'has_monitoring',
        'has_mediation',
        'has_emergency_support',
        'has_retraining',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'base_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'free_replacements' => 'integer',
        'sort_order' => 'integer',
        'has_supervision' => 'boolean',
        'has_monitoring' => 'boolean',
        'has_mediation' => 'boolean',
        'has_emergency_support' => 'boolean',
        'has_retraining' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    // Accessors
    public function getPriceDisplayAttribute(): string
    {
        $base = number_format($this->base_price);
        if ($this->max_price) {
            return '₦' . $base . ' – ₦' . number_format($this->max_price);
        }
        if ($this->slug === 'premium') {
            return '₦' . $base . '+';
        }
        return '₦' . $base;
    }

    public function getFeaturesListAttribute(): array
    {
        return is_array($this->features) ? $this->features : json_decode($this->features, true) ?? [];
    }

    public function getReplacementTextAttribute(): string
    {
        if ($this->free_replacements === -1) {
            return 'Free Replacement Anytime';
        }
        if ($this->free_replacements > 0) {
            return $this->free_replacements . ' Replacement' .
                ($this->free_replacements > 1 ? 's' : '') .
                ($this->replacement_window ? ' within ' . $this->replacement_window : '');
        }
        return 'No replacement included';
    }
}
