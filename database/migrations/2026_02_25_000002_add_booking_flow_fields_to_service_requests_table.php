<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // Location & scheduling
            $table->string('preferred_city')->nullable()->after('state_of_residence');
            $table->date('preferred_start_date')->nullable()->after('service_days');
            $table->integer('duration_weeks')->nullable()->after('days_per_week');

            // Management tier
            $table->string('management_plan')->nullable()->after('package');
            $table->decimal('management_plan_cost', 10, 2)->default(0)->after('management_plan');

            // Add-ons
            $table->boolean('express_matching')->default(false)->after('management_plan_cost');
            $table->decimal('express_matching_cost', 10, 2)->default(0)->after('express_matching');
            $table->boolean('monthly_protection')->default(false)->after('express_matching_cost');
            $table->decimal('monthly_protection_cost', 10, 2)->default(0)->after('monthly_protection');

            // Installment support
            $table->string('payment_type')->default('full')->after('payment_status');
            $table->decimal('amount_paid', 10, 2)->default(0)->after('payment_type');
            $table->decimal('amount_remaining', 10, 2)->default(0)->after('amount_paid');
            $table->date('balance_due_date')->nullable()->after('amount_remaining');

            // Staff matching state
            $table->string('matching_status')->default('pending')->after('status');
            $table->unsignedBigInteger('selected_staff_id')->nullable()->after('matching_status');
            $table->boolean('staff_details_released')->default(false)->after('selected_staff_id');
            $table->boolean('call_with_staff_requested')->default(false)->after('staff_details_released');

            // Deployment type
            $table->string('deployment_type')->nullable()->after('call_with_staff_requested');

            $table->foreign('selected_staff_id')->references('id')->on('users')->nullOnDelete();
            $table->index('matching_status');
            $table->index('management_plan');
            $table->index('preferred_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropForeign(['selected_staff_id']);
            $table->dropIndex(['matching_status']);
            $table->dropIndex(['management_plan']);
            $table->dropIndex(['preferred_start_date']);

            $table->dropColumn([
                'preferred_city',
                'preferred_start_date',
                'duration_weeks',
                'management_plan',
                'management_plan_cost',
                'express_matching',
                'express_matching_cost',
                'monthly_protection',
                'monthly_protection_cost',
                'payment_type',
                'amount_paid',
                'amount_remaining',
                'balance_due_date',
                'matching_status',
                'selected_staff_id',
                'staff_details_released',
                'call_with_staff_requested',
                'deployment_type',
            ]);
        });
    }
};
