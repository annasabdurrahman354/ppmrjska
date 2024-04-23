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
            $table->unsignedBigInteger('kurikulum_id');
            $table->foreign('kurikulum_id')->references('id')->on('kurikulum')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedTinyInteger('materi_id')->nullable();
            $table->string('materi_type')->nullable();
            $table->boolean('status_tercapai')->default(false);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plot_kurikulum');
    }
};
