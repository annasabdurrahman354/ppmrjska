<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('lokasi', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('alamat')->nullable();
            $table->string('jenis_lokasi');
            $table->string('deskripsi')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi');
    }
};
