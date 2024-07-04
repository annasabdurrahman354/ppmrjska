<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galeri', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('judul');
            $table->string('slug');
            $table->foreignId('kategori_id')->nullable()->references('id')->on('kategori')->nullOnDelete();
            $table->foreignUlid('pengunggah_id')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->string('seo_judul')->nullable();
            $table->text('seo_deskripsi')->nullable();
            $table->json('seo_keyword')->nullable();
            $table->timestamps();
        });

        Schema::create('foto_galeri', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('galeri_id')->references('id')->on('galeri')->cascadeOnDelete();
            //$table->string('berkas_foto');
            $table->text('deskripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('galeri');
        Schema::dropIfExists('foto_galeri');
    }
};
