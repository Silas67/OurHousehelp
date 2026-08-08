<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveInServiceRequest extends Model
{
    use HasFactory;

    // Specify the table name if it does not follow the Laravel naming convention
    protected $table = 'liveinservicerequests';

    // Allow mass assignment for the specified fields
    protected $fillable = [
        'client_id',
        'househelp_id',
        'status',
        'message',
        'specialties',
    ];

    // Cast attributes to specific data types
    protected $casts = [
        'requested_at' => 'datetime', // Automatically handle datetime conversions
        'specialties' => 'array', // Automatically handle JSON to array conversion
    ];

    /**
     * Relationship: The house help associated with this service request.
     */
    public function househelp()
    {
        return $this->belongsTo(User::class, 'househelp_id');
    }

    /**
     * Relationship: The client who made the service request.
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
