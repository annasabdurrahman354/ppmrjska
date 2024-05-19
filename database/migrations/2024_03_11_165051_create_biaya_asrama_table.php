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
        Schema::disableForeignKeyConstraints();

        Schema::create('biaya_asrama', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('tahun_ajaran');
            $table->foreignUlid('asrama_id')->nullable()->references('id')->on('asrama')->cascadeOnUpdate()->nullOnDelete();
            $table->integer('biaya_kamar_tahunan');
            $table->boolean('dibayar_ke_bendahara');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biaya_asrama');
    }
};
