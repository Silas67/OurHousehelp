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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id'); // References users table (clients)
            $table->unsignedBigInteger('house_help_id'); // References house help user id
            $table->unsignedBigInteger('house_help_assist_id')->nullable(); // References assistant id
            $table->date('assigned_at');
            $table->string('status')->default('active'); // Can be 'active', 'completed', 'canceled'
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('house_help_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('house_help_assist_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
