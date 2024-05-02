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

        Schema::create('plot_kurikulum_materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plot_kurikulum_id')->references('id')->on('plot_kurikulum')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedTinyInteger('materi_id')->nullable();
            $table->string('materi_type')->nullable();
            $table->boolean('status_tercapai')->default(false);
            $table->timestamps();
            $table->softDeletes();
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
