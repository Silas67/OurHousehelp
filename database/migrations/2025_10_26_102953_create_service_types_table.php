<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Fixed system code (PMS, PSS, PRS)
            $table->string('name');           // Fixed descriptive name
            $table->decimal('cost', 10, 2)->default(0); // Editable by admin
            $table->timestamps();
        });

        // Seed permanent records — cannot be deleted or altered except cost
        DB::table('service_types')->insert([
            ['code' => 'PMS', 'name' => 'Premium Max Service', 'cost' => 25000],
            ['code' => 'PSS', 'name' => 'Premium Special Service', 'cost' => 10000],
            ['code' => 'PRS', 'name' => 'Premium Regular Service', 'cost' => 0],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('service_types');
    }
};
