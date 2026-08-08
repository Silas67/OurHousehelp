<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'rated_by', 'rating', 'comment'];

    // Define relationship to the applicant (user being rated)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define relationship to the user who rated
    public function ratedBy()
    {
        return $this->belongsTo(User::class, 'rated_by');
    }

}
