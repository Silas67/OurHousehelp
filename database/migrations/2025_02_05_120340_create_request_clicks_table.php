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
        // Schema::create('request_clicks', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('user_id')->constrained()->onDelete('cascade');
        //     $table->foreignId('payment_id')->constrained()->onDelete('cascade');
        //     // Store which househelp was clicked (you may also add a foreign key constraint if needed)
        //     $table->unsignedBigInteger('househelp_id');
        //     $table->timestamps();
        // });

        // Schema::create('request_clicks', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('user_id')->constrained()->onDelete('cascade');
        //     $table->foreignId('payment_id')->constrained()->onDelete('cascade');
        
        //     // Fix foreign key reference to match table name
        //     $table->unsignedBigInteger('househelp_id');
        //     // $table->foreign('househelp_id')->references('id')->on('house_helps')->onDelete('cascade'); // Corrected table name
        //     $table->foreignId('househelp_id')->constrained('users')->onDelete('cascade');

        //     $table->timestamps();
        // });       


        Schema::create('request_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            // $table->integer('click_count')->default(0);
            // Corrected foreign key reference to point to the users table
            $table->foreignId('househelp_id')->constrained('users')->onDelete('cascade');
        
            $table->timestamps();
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_clicks');
    }
};
