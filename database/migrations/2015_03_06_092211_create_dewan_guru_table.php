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

        Schema::create('dewan_guru', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nama', 96);
            $table->string('nama_panggilan', 64);
            //$table->string('avatar')->nullable();
            $table->string('nomor_telepon', 16);
            $table->string('email', 96)->nullable()->unique();
            $table->string('alamat')->nullable();
            $table->boolean('status_aktif');
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
        Schema::dropIfExists('dewan_guru');
    }
};
