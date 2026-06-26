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
    Schema::create('rooms', function (Blueprint $table) {
        $table->string('roomID', 50)->primary(); // e.g. K102, S345
        $table->string('buildingName', 100);    // Kolej Kasa or Kolej Sutera
        $table->string('wing', 50);             // Wing A or Wing B
        $table->integer('minCapacity')->default(4);
        $table->integer('maxCapacity')->default(4);
        $table->integer('currentOccupancy')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
