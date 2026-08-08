<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_message_id')->constrained('contact_messages')->onDelete('cascade'); // Links to contact_messages table
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade'); // Admin who created the reply
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // Original message owner, nullable if only admins reply
            $table->boolean('read_status')->default(false); // False means unread, true means read
            $table->text('reply_message');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replies');
    }
};
