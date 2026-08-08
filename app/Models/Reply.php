<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_message_id',
        'admin_id',
        'user_id',
        'reply_message',
    ];


    // A reply belongs to a contact message
    public function contactMessage()
    {
        return $this->belongsTo(ContactMessage::class);
    }

    // A reply belongs to an admin who authored it
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // A reply belongs to the user who sent the original message
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }






    protected static function booted()
    {
        static::created(function ($reply) {
            $contactMessage = $reply->contactMessage;
            $contactMessage->read_status = false; // Mark as unread when a new reply is created
            $contactMessage->save();
        });
    }
}
