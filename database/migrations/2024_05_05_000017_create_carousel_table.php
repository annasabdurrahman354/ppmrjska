<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('carousel', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('judul');
            $table->string('link_tujuan')->nullable();
            $table->boolean('status_aktif')->default(0)->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('carousel');
    }
};
