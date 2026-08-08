<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->integer('user_role')->default(0);

            $table->string('name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('state_of_origin')->nullable();
            $table->string('state_of_residence')->nullable();
            $table->string('lga_of_origin')->nullable();
            $table->string('country')->nullable();
            $table->string('country_of_residence')->nullable();
            $table->string('address', 2048)->nullable();
            $table->string('landmark')->nullable();

            // banking
            $table->string('account_name')->nullable();
            $table->string('bank')->nullable();
            $table->string('account_number')->nullable();

            $table->string('nin')->nullable();
            $table->boolean('nin_verified')->default(false);
            $table->timestamp('nin_verified_at')->nullable();

            // househelps and skilled workers
            $table->string('id_card')->nullable();
            $table->boolean('availability')->default(true);
            $table->string('approval_status')->default('pending');
            $table->string('status')->default('active');

            $table->string('years_of_experience')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->string('account_type'); // "client" or "applicant(househelp)" or "admin" or "skilled worker"
            $table->string('applicant_type')->nullable(); // "live-in", "live-out", or "both", Skilled-Worker

            // Registration tracking
            $table->boolean('is_registered')->default(false);
            $table->timestamp('registration_date')->nullable();
            $table->timestamp('registration_expires_at')->nullable();
            $table->string('registration_status')->default('inactive'); // inactive, active, expired

            $table->boolean('form_completed')->default(false);
            $table->boolean('guarantors_forms_uploaded')->default(false);
            $table->boolean('guarantors_forms_uploaded_2')->default(false);
            $table->boolean('terms_accepted')->default(false);
            $table->text('bio')->nullable();
            $table->json('specialties')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
