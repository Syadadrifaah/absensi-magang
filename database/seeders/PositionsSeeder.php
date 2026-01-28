<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $positions = [
            ['name' => 'Direktur',        'level' => 1],
            ['name' => 'Manager',         'level' => 2],
            ['name' => 'Supervisor',      'level' => 3],
            ['name' => 'Staff',            'level' => 4],
            ['name' => 'Admin',            'level' => 4],
            ['name' => 'Magang',           'level' => 5],
        ];

        foreach ($positions as $position) {
            DB::table('positions')->insert([
                'name'       => $position['name'],
                'level'      => $position['level'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
