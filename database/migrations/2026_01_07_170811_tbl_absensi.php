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

    // user_id sementara NULL
    $table->foreignId('user_id')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->foreignId('lokasi_id')
        ->nullable()
        ->constrained('tbl_lokasi')
        ->nullOnDelete();

    $table->date('tanggal');
    $table->time('jam');

    $table->enum('status', ['hadir', 'izin', 'sakit'])->default('hadir');

    // koordinat sebagai STRING
    $table->string('koordinat_user')->nullable();

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
