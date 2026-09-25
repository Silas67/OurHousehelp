<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('corporate_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_name');
            $table->string('work_email');
            $table->string('phone');
            $table->unsignedInteger('staff_count')->default(1);
            // Stored as a JSON array, matching the existing pattern used for
            // 'specialties' / 'serviceName' elsewhere in this codebase.
            $table->json('staff_types');
            $table->date('preferred_start_date')->nullable();
            $table->text('notes')->nullable();
            // Admin's own follow-up notes - separate from the customer's
            // 'notes' field above, which is what they submitted with the form.
            $table->text('admin_notes')->nullable();
            // new -> contacted -> quoted -> closed / lost. Plain admin-managed
            // status, no client-facing tracking - this is a lead, not a booking.
            $table->string('status')->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corporate_inquiries');
    }
};