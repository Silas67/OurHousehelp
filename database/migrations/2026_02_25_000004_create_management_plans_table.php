<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('management_plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('label')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->decimal('max_price', 10, 2)->nullable();
            $table->json('features');
            $table->integer('free_replacements')->default(0);
            $table->string('replacement_window')->nullable();
            $table->boolean('has_supervision')->default(false);
            $table->boolean('has_monitoring')->default(false);
            $table->boolean('has_mediation')->default(false);
            $table->boolean('has_emergency_support')->default(false);
            $table->boolean('has_retraining')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('management_plans');
    }
};
