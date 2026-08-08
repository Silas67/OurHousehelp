<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'subject',
        'message',
        'message_type',  // Include message_type
        'user_id',  // Include user_id
    ];

 // A contact message belongs to a user (nullable if the message is from a guest)
 public function user()
 {
     return $this->belongsTo(User::class);
 }

 // A contact message has many replies
 public function replies()
 {
     return $this->hasMany(Reply::class);
 }


}
