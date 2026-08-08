<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePackageCost extends Model
{
    use HasFactory;
    protected $fillable = ['service_id', 'name', 'cost'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }


    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'package_cost', 'id');
    }
}
