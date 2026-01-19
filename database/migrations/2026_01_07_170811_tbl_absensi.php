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
        Schema::create('tbl_absensi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('lokasi_id')
                ->nullable()
                ->constrained('tbl_lokasi')
                ->nullOnDelete();

            $table->date('tanggal');

            // JAM ABSENSI
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();

            $table->enum('status', ['Hadir', 'Alpha', 'Izin', 'Sakit'])->default('Hadir');
            $table->enum('keterangan', ['Terlambat', 'Pulang_Cepat','Tepat_Waktu', 'Terlambat_Pulang_Cepat'])->default('Tepat_Waktu');

            // FOTO ABSENSI
            $table->string('foto_masuk')->nullable();
            $table->string('foto_pulang')->nullable();

            // KOORDINAT
            $table->string('koordinat_masuk')->nullable();
            $table->string('koordinat_pulang')->nullable();

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
