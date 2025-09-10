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
        Schema::create('vending_machines', function (Blueprint $table) {
            $table->id();
            $table->string('name');       // 自販機の名前
            $table->decimal('latitude', 10, 7);   // 緯度
            $table->decimal('longitude', 10, 7);  // 経度
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vending_machines');
    }
};
