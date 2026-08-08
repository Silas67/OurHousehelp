<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('payment_for');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable();
            $table->string('transaction_reference')->unique()->nullable();
            $table->string('status')->default('pending');
            $table->json('payment_details')->nullable();
            $table->string('bank_receipt')->nullable();
            $table->string('whatsapp_receipt')->nullable();
            $table->boolean('is_installment')->default(false);
            $table->string('installment_type')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index(['service_request_id', 'payment_for']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_payments');
    }
};
