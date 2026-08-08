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
        Schema::create('house_helps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('account_type'); // "client" or "applicant"
            $table->string('phone')->nullable(); // Phone number
            $table->string('gender')->nullable();
            $table->date('dob')->nullable(); // Date of Birth
            $table->string('state_of_origin')->nullable(); // State of origin
            $table->string('state_of_residence')->nullable(); // State of residence
            $table->string('lga_of_origin')->nullable(); // Local Government Area of the state of origin
            $table->string('years_of_experience')->nullable(); // Years of experience
            $table->string('profile_photo_path', 2048)->nullable();
            $table->string('address', 2048)->nullable();
            $table->string('nin')->nullable(); // National Identification Number
            $table->string('id_card')->nullable(); // File path of the uploaded ID card image
            $table->integer('user_role')->default(0); // Super Admin (2), Admin (1), User (0)
            $table->boolean('availability')->default(true); // Availability status
            $table->text('bio')->nullable(); // Biography field
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house_helps');
    }
};
