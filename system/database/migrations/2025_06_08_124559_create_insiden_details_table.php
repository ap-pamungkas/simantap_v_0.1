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
        Schema::create('insiden_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insiden_id')->constrained('insiden')->onDelete('cascade');
            $table->foreignId('LogPetugas_id')->constrained('log_petugas')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insiden_details');
    }
};
