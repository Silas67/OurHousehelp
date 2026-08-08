<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('languages')->nullable()->after('specialties');
            $table->string('city')->nullable()->after('state_of_residence');
            $table->date('available_from')->nullable()->after('availability');
            $table->boolean('address_confirmed')->default(false)->after('landmark');
            $table->boolean('background_checked')->default(false)->after('address_confirmed');
            $table->boolean('id_verified')->default(false)->after('background_checked');
            $table->text('work_summary')->nullable()->after('bio');

            $table->index('city');
            $table->index('available_from');
            $table->index(['account_type', 'approval_status', 'availability']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['city']);
            $table->dropIndex(['available_from']);
            $table->dropIndex(['account_type', 'approval_status', 'availability']);

            $table->dropColumn([
                'languages',
                'city',
                'available_from',
                'address_confirmed',
                'background_checked',
                'id_verified',
                'work_summary',
            ]);
        });
    }
};
