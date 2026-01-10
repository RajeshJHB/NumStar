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
        Schema::table('clients', function (Blueprint $table) {
            $table->time('time_of_birth')->nullable()->after('date_of_birth');
            $table->string('sex')->nullable()->after('time_of_birth');
            $table->string('country_of_birth')->nullable()->after('sex');
            $table->string('town_of_birth')->nullable()->after('country_of_birth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['time_of_birth', 'sex', 'country_of_birth', 'town_of_birth']);
        });
    }
};
