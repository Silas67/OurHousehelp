<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_shortlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->constrained('users')->onDelete('cascade');
            $table->integer('match_score')->default(0);
            $table->json('match_reasons')->nullable();
            $table->enum('status', ['notified', 'accepted', 'declined', 'shortlisted', 'selected', 'expired'])
                ->default('notified');
            $table->boolean('client_visible')->default(false);
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamp('shortlisted_at')->nullable();
            $table->timestamp('selected_at')->nullable();
            $table->timestamps();

            $table->unique(['service_request_id', 'staff_id']);
            $table->index(['service_request_id', 'status']);
            $table->index(['staff_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_shortlists');
    }
};
