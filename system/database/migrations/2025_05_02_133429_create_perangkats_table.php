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
        Schema::create('perangkat', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('no_seri')->nullable();
            $table->string('qr_code');
            $table->enum('status', ['Aktif', 'Tidak Aktif']);
            $table->enum('kondisi', ['Baik', 'Rusak']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perangkat');
    }
};
