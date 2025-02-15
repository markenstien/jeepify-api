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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->longText('pick_up_details');
            $table->longText('drop_off_details');
            $table->date('booking_date')->nullable();
            $table->smallInteger('vehicle_type')->nullable();
            $table->time('estimated_arrival_time')->nullable();
            $table->string('estimated_travel_time', length: 10);
            $table->double('tip')->nullable();
            $table->double('discount')->nullable();
            $table->double('initial_cost')->nullable();
            $table->double('net_cost')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('driver_id')->nullable();
            $table->string('notes', length:150)->nullable();
            $table->string('parcel_description', length:150)->nullable();
            $table->enum('booking_status', ['pending', 'in-progress', 'cancelled','delivered', 'delivered-cancelled','in-progress-cancelled'])->default('pending');
            $table->string('booking_reference_number', length:12)->unique();
            $table->string('distance', length:5)->comment('In Killometers',);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
