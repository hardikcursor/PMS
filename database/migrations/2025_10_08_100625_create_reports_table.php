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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade')->onUpdate('cascade');
            $table->string('serial_number');
            $table->string('bill_no');
            $table->string('vehicle_no')->nullable();
            $table->string('duration_type');
            $table->string('in_time');
            $table->string('out_time');
            $table->string('date');
            $table->integer('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
