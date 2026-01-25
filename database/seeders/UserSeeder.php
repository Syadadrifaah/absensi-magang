<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Andi Pratama',   'email' => 'andi.pratama@mail.com'],
            ['name' => 'Budi Santoso',   'email' => 'budi.santoso@mail.com'],
            ['name' => 'Citra Lestari',  'email' => 'citra.lestari@mail.com'],
            ['name' => 'Dewi Anggraini', 'email' => 'dewi.anggraini@mail.com'],
        ];

        // Ambil role_id yang benar-benar ada di database
        $roleIds = Role::pluck('id')->toArray();

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']], // biar tidak duplikat
                [
                    'name'     => $user['name'],
                    'password' => Hash::make('password'),
                    'role_id'  => $roleIds[array_rand($roleIds)],
                ]
            );
        }
    }
}
