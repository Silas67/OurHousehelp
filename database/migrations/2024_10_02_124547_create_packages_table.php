<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Package name
            $table->decimal('cost', 10, 2)->default(0); // Package cost
            $table->text('description')->nullable();

            // Optional marketing points
            $table->text('point1')->nullable();
            $table->text('point2')->nullable();
            $table->text('point3')->nullable();

            // Service meta info
            $table->unsignedInteger('days_per_week')->default(1);
            $table->decimal('percentage', 5, 2)->default(0);

            // Working hours (up to 5 possible slots)
            for ($i = 1; $i <= 5; $i++) {
                $table->time("working_hours_start_$i")->nullable();
                $table->time("working_hours_end_$i")->nullable();
            }

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('packages');
    }
}
