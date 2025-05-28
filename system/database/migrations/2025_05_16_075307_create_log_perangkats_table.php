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
        Schema::create('log_perangkat', function (Blueprint $table) {
            $table->id();
            $table->char('id_perangkat', 36);
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->decimal('kualitas_udara', 8,2)->nullable();
            $table->decimal('suhu', 5, 2)->nullable();
            $table->string('status');
            $table->timestamps();

            $table->foreign('id_perangkat')->references('id')->on('perangkat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_perangkats');
    }
};
