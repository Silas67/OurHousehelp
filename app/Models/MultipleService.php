<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultipleService extends Model
{
    use HasFactory;

    protected $fillable = ['cost', 'description', 'services', 'working_hours']; // Allow filling services as well

    // Cast the services attribute to an array so that you can access it easily
    protected $casts = [
        'services' => 'array',  // Automatically decode JSON to array
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'multiple_services');
    }
}
