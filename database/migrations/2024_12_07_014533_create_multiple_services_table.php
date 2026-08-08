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
        Schema::create('multiple_services', function (Blueprint $table) {
            $table->id();
            $table->json('services'); // Store selected services as JSON
            $table->decimal('cost', 10, 2); // Cost of the services
            $table->integer('working_hours')->nullable(); // Total working hours for the services
            $table->text('description')->nullable(); // Additional information
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multiple_services');
    }
};
