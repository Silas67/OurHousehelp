<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraDayCost extends Model
{
    use HasFactory;

    protected $fillable = ['cost', 'description'];
}
