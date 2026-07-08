<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hostel_users', function (Blueprint $table) {
            // Adds gender column defaulting to 'Male' so existing male test accounts don't break
            $table->string('gender', 10)->default('Male')->after('userName');
        });
    }

    public function down(): void
    {
        Schema::table('hostel_users', function (Blueprint $table) {
            $table->dropColumn('gender');
        });
    }
};