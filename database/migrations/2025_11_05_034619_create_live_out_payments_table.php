<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveOutPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('live_out_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable();
            $table->string('transaction_reference')->unique()->nullable();
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->text('payment_details')->nullable(); // JSON data for payment info
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('transaction_reference');
        });
    }

    public function down()
    {
        Schema::dropIfExists('live_out_payments');
    }
}