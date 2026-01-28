<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KategoriEmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $kategori = [
            'Pegawai Tetap',
            'Pegawai Kontrak',
            'Pegawai Honorer',
            'Magang',
            'Outsourcing',
        ];

        foreach ($kategori as $nama) {
            DB::table('kategori_employees')->insert([
                'nama_kategori' => $nama,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
