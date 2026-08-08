<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // The applicant being rated
            $table->foreignId('rated_by')->constrained('users')->onDelete('cascade'); // The user who is giving the rating
            $table->integer('rating'); // Rating from 1 to 5
            $table->text('comment')->nullable(); // Optional comment for the rating
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
