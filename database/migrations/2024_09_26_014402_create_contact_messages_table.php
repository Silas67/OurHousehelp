<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('subject');
            $table->text('message');
            $table->enum('message_type', ['user', 'guest']); // Add message_type column
            $table->boolean('read_status')->default(false); // False means unread, true means read
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Make user_id nullable
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('contact_messages');
    }
}
