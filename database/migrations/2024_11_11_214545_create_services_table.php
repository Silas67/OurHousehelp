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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('service_name'); // Name of the service
            $table->decimal('base_cost', 8, 2)->nullable(); // Base cost for the service
            $table->text('about_service'); // Description of the service
            $table->string('icon_class')->nullable(); // CSS class for service icon
            $table->integer('hours_of_work')->nullable(); // Hours of work for the service
            $table->enum('service_type', ['PMS', 'PSS', 'PRS']); // Type of service: PMS (Max), PSS (Special), PRS (Regular)
            $table->boolean('number_of_apartment')->default(false); // Whether the service considers apartment count
            $table->longText('dos_and_donts')->nullable(); // Details for modal display
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
