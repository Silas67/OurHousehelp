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
        Schema::create('confirmations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_request_id'); // Reference to the service request
            $table->unsignedBigInteger('admin_id'); // Admin performing the confirmation
            $table->unsignedBigInteger('house_help_id')->nullable(); // Assigned house help
            $table->unsignedBigInteger('house_help_assist_id')->nullable(); // Assigned house help assistant
            $table->date('assigned_at')->nullable();
            $table->boolean('nin_verified')->default(false);
            $table->boolean('payment_verified')->default(false);
            $table->boolean('client_contacted')->default(false);
            $table->boolean('other_checks_completed')->default(false);
            $table->string('status')->default('pending'); // Status: pending, confirmed
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('service_request_id')->references('id')->on('service_requests')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('house_help_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('house_help_assist_id')->references('id')->on('users')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confirmations');
    }
};
