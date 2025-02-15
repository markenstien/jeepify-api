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
        Schema::create('booking_pickup_dropoffs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('action', ['PICKUP', 'DROPOFF']);
            $table->string('image_proof', length:255);
            $table->integer('booking_id');
            $table->enum('status',['success','unavailable','declined']);
            $table->string('notes', length: 150);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_pickup_dropoffs');
    }
};
