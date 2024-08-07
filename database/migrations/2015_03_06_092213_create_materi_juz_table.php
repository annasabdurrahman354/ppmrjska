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

        Schema::create('materi_juz', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('nama', 6);
            $table->unsignedSmallInteger('halaman_awal');
            $table->unsignedSmallInteger('halaman_akhir');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_juz');
    }
};
