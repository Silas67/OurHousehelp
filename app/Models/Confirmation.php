<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Confirmation extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'client_id',
        'admin_id',
        'house_help_id',
        'house_help_assist_id',
        'assigned_at',
        'nin_verified',
        'payment_verified',
        'client_contacted',
        'other_checks_completed',
        'status',
    ];

    protected $dates = ['assigned_at'];


    // Relationship to the client (User model)
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Relationship to the house help
    public function houseHelp()
    {
        return $this->belongsTo(User::class, 'house_help_id');
    }

    // Relationship to the assistant
    public function houseHelpAssistant()
    {
        return $this->belongsTo(User::class, 'house_help_assist_id'); // Assuming 'User' is your model for assistants
    }



    // Relationship with User for admin
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }



    // Relationship to the assistant house help assigned
    public function houseHelpAssist()
    {
        return $this->belongsTo(User::class, 'house_help_assist_id');
    }



    // Relationship to the service request
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

}
