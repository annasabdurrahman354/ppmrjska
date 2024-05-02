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
            $table->foreignUlid('calon_santri_id')->nullable()->references('id')->on('calon_santri')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignUlid('penguji_id')->nullable()->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->json('nilai_tes');
            $table->float('nilai_akhir')->nullable();
            $table->text('catatan_penguji')->nullable();
            $table->tinyInteger('rekomendasi_penguji')->nullable();
            $table->string('status_penerimaan');
            $table->timestamps();
            $table->softDeletes();
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
