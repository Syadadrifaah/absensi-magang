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
        Schema::create('tbl_pengaturan_absensi', function (Blueprint $table) {
            $table->id();
            $table->time('jam_masuk_mulai');   // absen dibuka
            $table->time('jam_masuk_selesai'); // batas datang
            $table->time('jam_pulang_mulai');  // mulai pulang
            $table->time('jam_pulang_selesai'); // batas pulang
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pengaturan_absensi');
    }
};
