<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change enum from 'both' to 'semi-live-in'
        DB::statement("ALTER TABLE service_requests MODIFY COLUMN applicant_type ENUM('live-in', 'live-out', 'semi-live-in') NULL");

        // Update existing records that have 'both' to 'semi-live-in'
        DB::table('service_requests')->where('applicant_type', 'both')->update(['applicant_type' => 'semi-live-in']);
    }

    public function down(): void
    {
        DB::table('service_requests')->where('applicant_type', 'semi-live-in')->update(['applicant_type' => 'both']);
        DB::statement("ALTER TABLE service_requests MODIFY COLUMN applicant_type ENUM('live-in', 'live-out', 'both') NULL");
    }
};
