<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseHelp extends Model
{
    use HasFactory;

    // HouseHelp.php (App\Models\HouseHelp)

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'house_help_id');
    }
}
