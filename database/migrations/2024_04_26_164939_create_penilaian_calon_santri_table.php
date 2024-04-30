<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePenilaianCalonSantriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penilaian_calon_santri', function (Blueprint $table) {
            $table->id();
            $table->ulid('calon_santri_id');
            $table->ulid('penguji_id');
            $table->unsignedInteger('nilai_bacaan');
            $table->unsignedInteger('nilai_pegon');
            $table->unsignedInteger('nilai_pengetahuan');
            $table->unsignedInteger('nilai_wawasan');
            $table->text('catatan_penguji')->nullable();
            $table->text('rekomendasi_penguji')->nullable();
            $table->string('status_penerimaan');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('calon_santri_id')->references('id')->on('calon_santri')->cascadeOnDelete();
            $table->foreign('penguji_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penilaian_calon_santri');
    }
}
