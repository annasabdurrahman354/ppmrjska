<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('users', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nama', 96);
            $table->string('nama_panggilan', 64);
            $table->string('jenis_kelamin');
            $table->string('nis', 9)->unique();
            $table->string('nomor_telepon', 16)->unique();
            $table->string('email', 96)->unique();
            $table->string('kelas');
            $table->unsignedInteger('angkatan_pondok');
            $table->string('status_pondok');
            $table->date('tanggal_lulus_pondok')->nullable();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            $table->rememberToken();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
