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
        Schema::create('redaksi_media', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('judul');
            $table->string('slug');
            $table->string('sumber');
            $table->string('link_tujuan')->nullable();
            $table->string('deskripsi')->nullable();
            $table->longText('embed')->nullable();
            $table->foreignId('kategori_id')->nullable()->references('id')->on('kategori')->nullOnDelete();
            $table->foreignUlid('pengunggah_id')->nullable()->references('id')->on('users')->nullOnDelete();
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
        Schema::dropIfExists('media');
    }
};
