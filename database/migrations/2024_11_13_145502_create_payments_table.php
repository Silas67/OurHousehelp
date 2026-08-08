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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key to users
            $table->unsignedBigInteger('payment_plan_id'); // Foreign key to payment_plans
            $table->string('plan_type'); // e.g., skilled worker, Househelp.
            $table->string('payment_frequency'); // E.g., Monthly, Weekly
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // Payment status (e.g., pending, approved, exhausted, expired, cancelled)
            $table->text('about_payment');
            $table->text('payment_method')->nullable();
            $table->integer('num_searches')->default(0);
            $table->integer('used_searches')->default(0); // Track searches used
            $table->string('receipt_path')->nullable(); // Field for storing the receipt file path
            $table->date('expiry_date')->nullable(); // Expiry date based on plan duration
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payment_plan_id')->references('id')->on('payment_plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
