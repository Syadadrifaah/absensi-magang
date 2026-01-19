<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $users = [
            'Andi Pratama', 'Budi Santoso', 'Citra Lestari', 'Dewi Anggraini',
            'Eko Saputra', 'Fajar Hidayat', 'Gita Permata', 'Hendra Wijaya',
            'Intan Maharani', 'Joko Susilo',
            'Kiki Amelia', 'Lukman Hakim', 'Maya Putri', 'Nanda Prakoso',
            'Oki Ramadhan', 'Putri Ayu', 'Rizki Maulana', 'Sari Wulandari',
            'Taufik Hidayat', 'Yuni Kartika'
        ];

        foreach ($users as $i => $name) {
            User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@mail.com',
                'password' => Hash::make('password'),
                'role_id' => rand(1, 3), // sesuaikan dengan role kamu
            ]);
        }
    }
}
