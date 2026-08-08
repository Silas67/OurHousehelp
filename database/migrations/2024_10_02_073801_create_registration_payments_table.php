<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('registration_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_request_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable();
            $table->string('transaction_reference')->unique()->nullable();
            $table->string('status')->default('pending'); // pending, completed, failed, refunded
            $table->text('payment_details')->nullable(); // JSON data for payment info
            $table->string('bank_receipt')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // When this payment registration expires
            $table->timestamps();
            
            $table->index('status');
            $table->index('transaction_reference');
            $table->index('expires_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('registration_payments');
    }
}
