<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('kotak_saran', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pengirim')->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->longText('isi');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('kotak_saran');
    }
};
