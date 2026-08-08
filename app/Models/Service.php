<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'about_service',
        'base_cost',
        'icon_class',        // Icon class for representing the service
        'hours_of_work',     // Number of hours for the service
        'service_type',      // Indicates the type of service (e.g., premium)
        'dos_and_donts',     // JSON: Dos and Don'ts of the service
        'number_of_apartment', // Number of apartments applicable for this service
        'packages',          // JSON: Contains details of packages for this service

        'description',
        'requires_apartment',
    ];

    protected $casts = [
        'packages' => 'array',
        'dos_and_donts' => 'array',
        'package_cost' => 'float', // Cast to float for numeric value
    ];

    // Many-to-Many: Services related to multiple service entries
    public function multipleServices()
    {
        return $this->belongsToMany(MultipleService::class, 'multiple_services', 'service_id', 'multiple_service_id');
    }

    // One-to-Many: Service linked to package costs
    public function servicePackageCosts()
    {
        return $this->hasMany(ServicePackageCost::class, 'service_id');
    }

    // Many-to-Many: Services within a package
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_package_service', 'package_cost_id', 'service_id');
    }


    // In Service.php (Service model)
    public function apartments()
    {
        return $this->hasMany(Apartments::class);
    }

    // Belongs to ServiceType
    public function type()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    // Many-to-Many: Services within a package (related services)
    public function relatedServices()
    {
        return $this->belongsToMany(Service::class, 'service_package_service', 'package_cost_id', 'service_id');
    }


    // Service requests for this service
    // public function serviceRequests()
    // {
    //     return $this->hasMany(ServiceRequest::class);
    // }

    public function serviceRequests()
    {
        return $this->belongsToMany(ServiceRequest::class, 'service_request_service', 'service_id', 'service_request_id')
            ->withTimestamps();
    }
}
