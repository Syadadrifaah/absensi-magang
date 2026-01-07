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
        //
        Schema::create('tbl_logbook', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable() // 🔴 PENTING
                ->constrained('users')
                ->nullOnDelete();

            $table->date('tanggal');
            $table->text('kegiatan');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
