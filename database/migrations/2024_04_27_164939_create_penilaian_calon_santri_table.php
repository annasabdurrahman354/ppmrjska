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

        Schema::create('penilaian_calon_santri', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('calon_santri_id')->references('id')->on('calon_santri')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('penguji_id')->nullable()->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->json('nilai_tes');
            $table->float('nilai_akhir');
            $table->text('catatan_penguji');
            $table->tinyInteger('rekomendasi_penguji');
            $table->string('status_penerimaan');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_calon_santri');
    }
};
