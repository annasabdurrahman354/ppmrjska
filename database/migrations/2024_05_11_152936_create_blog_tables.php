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
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('blog', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('deskripsi');
            $table->longText('konten');
            $table->foreignId('kategori_id')->nullable()->references('id')->on('kategori')->nullOnDelete();
            $table->foreignUlid('penulis_id')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->boolean('highlight');
            $table->string('seo_judul')->nullable();
            $table->text('seo_deskripsi')->nullable();
            $table->json('seo_keyword')->nullable();
            $table->enum('status', ['tertunda','terjadwal', 'terbit'])->default('tertunda');
            $table->dateTime('diterbitkan_pada')->nullable();
            $table->dateTime('dijadwalkan_pada')->nullable();
            $table->timestamps();
        });

        Schema::create('komentar_blog', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignUlid('blog_id')->references('id')->on('blog')->cascadeOnDelete();
            $table->text('komentar');
            $table->boolean('status_disetujui')->default(true);
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
        Schema::dropIfExists('komentar_blog');
        Schema::dropIfExists('blog');
        Schema::dropIfExists('kategori');
    }
};
