<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('service_charge_id')->nullable();
            $table->foreign('service_charge_id')->references('id')->on('service_charges');

            // 🧍 Client Information
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('name'); // exists in the user table
            $table->string('middle_name')->nullable(); // exists in the user table
            $table->string('last_name'); // exists in the user table
            $table->string('email'); // exists in the user table
            $table->string('phone', 20); // exists in the user table
            $table->enum('gender', ['male', 'female', 'no_preference']);  // exists in the user table
            $table->string('country_of_residence'); // exists in the user table
            $table->string('state_of_residence'); // exists in the user table
            $table->string('address'); // exists in the user table
            $table->string('landmark')->nullable(); // exists in the user table

            // 🎯 Service details
            $table->string('package');
            $table->enum('applicant_type', ['live-in', 'live-out', 'both'])->nullable();
            $table->integer('days_per_week');
            $table->text('service_days')->nullable();
            $table->json('serviceName');
            $table->json('features')->nullable();
            $table->string('working_hour')->nullable();
            $table->enum('on_behalf', ['yes', 'no'])->default('no');

            // 🎂 Optional DOB
            $table->unsignedTinyInteger('dob_month')->nullable();
            $table->unsignedTinyInteger('dob_day')->nullable();

            // 💰 Cost breakdown
            $table->decimal('base_cost', 8, 2)->default(0);
            $table->decimal('feature_cost', 10, 2)->default(0);
            $table->decimal('extra_days_cost', 10, 2)->default(0);
            $table->decimal('extra_service_cost', 10, 2)->default(0);
            $table->decimal('service_charge', 10, 2)->default(10000);
            $table->decimal('package_cost', 10, 2)->default(0);
            $table->decimal('grand_cost', 12, 2)->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->decimal('discounted_cost', 12, 2)->nullable();

            // Registration tracking
            $table->boolean('registration_paid')->default(false);
            $table->timestamp('registration_paid_at')->nullable();
            $table->timestamp('registration_expires_at')->nullable();
            $table->string('registration_status')->default('pending'); // pending, active, expired

            // 🧾 Status
            $table->string('status')->default('pending');
            $table->enum('payment_status', ['Paid', 'Pending', 'Failed', 'Pending Approval', 'Refunded'])->default('Pending');

            // 🪪 Verification
            $table->string('nin')->nullable();
            $table->string('id_card')->nullable();
            $table->string('passport')->nullable();

            // 📝 Miscellaneous
            $table->text('details')->nullable();

            $table->integer('bonus_slots_total')->default(0);
            $table->integer('bonus_slots_used')->default(0);

            $table->timestamps();

            // Indexes for better performance
            $table->index('registration_status');
            $table->index('registration_expires_at');
            $table->index(['user_id', 'registration_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
