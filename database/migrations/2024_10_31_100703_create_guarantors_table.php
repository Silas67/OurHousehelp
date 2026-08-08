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
        Schema::create('guarantors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('users')->onDelete('cascade');
            $table->integer('guarantor1_number')->nullable();
            $table->string('guarantor1_first_name');
            $table->string('guarantor1_last_name');
            $table->string('guarantor1_email')->nullable();
            $table->string('guarantor1_phone');
            $table->string('guarantor1_relationship');
            $table->string('guarantor1_occupation');
            $table->string('guarantor1_address');
            $table->string('guarantor1_passport_path')->nullable();
            $table->string('guarantor1_signature_path')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guarantors');
    }
};
