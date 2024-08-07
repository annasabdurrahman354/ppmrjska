<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('plot_kurikulum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kurikulum_id')->references('id')->on('kurikulum')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('jenjang_kelas');
            $table->unsignedTinyInteger('semester');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurikulum');
    }
};
