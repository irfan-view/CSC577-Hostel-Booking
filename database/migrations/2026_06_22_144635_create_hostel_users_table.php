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
    Schema::create('hostel_users', function (Blueprint $table) {
        $table->string('userID', 50)->primary(); // Unique Identifier for the user
        $table->string('userName', 100);          // User's login name
        $table->string('passwordHash', 255);      // Encrypted user password hash
        $table->string('accountStatus', 20)->default('Active'); // Operational state
        $table->integer('strikeCount')->default(0); // Number of policy violations
        $table->timestamps();                     // Automatically adds created_at and updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hostel_users');
    }
};
