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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('user_id');
            $table->enum('approval_status', ['pending','approved','declined'])->default('pending');
            $table->string('approval_remarks', length: 100)->nullable(true);
            $table->integer('approval_by')->nullable(true);
            $table->enum('vehicle_type',['1','2','3','4','5','6']);
            $table->string('plate_number', length:50);
            $table->string('brand', length:100);
            $table->string('year', length:4);
            $table->string('model', length:100);
            $table->string('color_description', length:100);
            $table->string('car_front_image');
            $table->string('car_back_image');
            $table->string('car_sideleft_image');
            $table->string('car_sideright_image');
        });

        Schema::create('drivers_license', function(Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('user_id');
            $table->string('license_expiry', length:5);
            $table->string('license_number', length:50);
            $table->string('license_restrictions', length:20);
            $table->string('license_front_image');
            $table->string('license_back_image');
            $table->enum('license_approval_status',['pending', 'approved', 'declined'])->default('pending');
            $table->string('license_approval_remarks', length:255);
            $table->integer('license_approval_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vechicles');
        Schema::dropIfExists('drivers_license');
    }
};
