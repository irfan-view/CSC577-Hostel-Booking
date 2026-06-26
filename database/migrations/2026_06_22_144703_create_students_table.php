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
    Schema::create('students', function (Blueprint $table) {
        $table->string('userID', 50)->primary();
        $table->string('programCode', 20);
        $table->string('email', 100)->unique();
        $table->timestamps();

        // Connects student userID to hostel_users userID (Foreign Key constraint)
        $table->foreign('userID')->references('userID')->on('hostel_users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
