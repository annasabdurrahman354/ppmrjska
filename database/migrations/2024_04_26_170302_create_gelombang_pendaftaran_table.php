<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGelombangPendaftaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gelombang_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->ulid('pendaftaran_id');
            $table->unsignedInteger('nomor_gelombang');
            $table->date('tanggal_awal_pendaftaran');
            $table->date('tanggal_akhir_pendaftaran');
            $table->date('tanggal_tes')->nullable();
            $table->date('tanggal_pengumuman')->nullable();
            $table->string('link_grup')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pendaftaran_id')->references('id')->on('pendaftaran')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gelombang_pendaftaran');
    }
}
