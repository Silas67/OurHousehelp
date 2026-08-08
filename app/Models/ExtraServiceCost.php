<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraServiceCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'cost',
        'description',
    ];
}
