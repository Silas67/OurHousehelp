<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    protected $fillable = ['cost']; // ✅ Only cost is editable

    protected $guarded = ['id', 'code', 'name']; // Prevent mass assignment on these

    public function services()
    {
        return $this->hasMany(Service::class, 'service_type_id');
    }
}

