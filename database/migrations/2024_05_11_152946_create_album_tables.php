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
        Schema::disableForeignKeyConstraints();

        Schema::create('album', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('deskripsi');
            $table->foreignId('kategori_id')->nullable()->references('id')->on('kategori')->nullOnDelete();
            $table->foreignUlid('pengunggah_id')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->boolean('highlight');
            $table->string('seo_judul')->nullable();
            $table->text('seo_deskripsi')->nullable();
            $table->json('seo_keyword')->nullable();
            $table->timestamps();
        });

        Schema::create('foto_album', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('album_id')->references('id')->on('album')->cascadeOnDelete();
            $table->text('deskripsi');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('foto_galeri');
        Schema::dropIfExists('galeri');
    }
};
