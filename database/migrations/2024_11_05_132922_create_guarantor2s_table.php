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
        Schema::create('guarantor2s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('users')->onDelete('cascade');
            $table->integer('guarantor2_number')->nullable();
            $table->string('guarantor2_first_name');
            $table->string('guarantor2_last_name');
            $table->string('guarantor2_email')->nullable();
            $table->string('guarantor2_phone');
            $table->string('guarantor2_relationship');
            $table->string('guarantor2_occupation');
            $table->string('guarantor2_address');
            $table->string('guarantor2_passport_path')->nullable();
            $table->string('guarantor2_signature_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guarantor2s');
    }
};
