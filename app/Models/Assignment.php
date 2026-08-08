<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['client_id', 'house_help_id', 'house_help_assist_id', 'assigned_at', 'status'];

    public function client() {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function houseHelp() {
        return $this->belongsTo(User::class, 'house_help_id');
    }

    public function houseHelpAssistant() {
        return $this->belongsTo(User::class, 'house_help_assist_id');
    }
}

