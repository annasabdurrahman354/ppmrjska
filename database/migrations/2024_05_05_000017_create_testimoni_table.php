<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('testimoni', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alumni');
            $table->string('tahun_lulus');
            $table->text('isi');
            $table->boolean('highlight');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('testimoni');
    }
};
