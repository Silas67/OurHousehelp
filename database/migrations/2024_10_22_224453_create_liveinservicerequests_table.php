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

        Schema::create('liveinservicerequests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade'); // ID of the client making the request
            $table->foreignId('househelp_id')->constrained('users')->onDelete('cascade'); // ID of the selected house help
            $table->string('status')->default('pending'); // Status of the request ('pending', 'accepted', 'rejected')
            $table->text('message')->nullable(); // Optional message sent by the client
            $table->json('specialties')->nullable(); // Store specialties as JSON (nullable)
            $table->timestamp('requested_at')->useCurrent(); // Timestamp when the request was made
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liveinservicerequests');
    }
};
