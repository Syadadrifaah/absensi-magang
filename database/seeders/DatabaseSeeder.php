<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(RoleSeeder::class);
        $this->call(KategoriEmployeesSeeder::class);
        $this->call(PositionsSeeder::class);
        $this->call(DummyUserSeeder::class);
        $this->call(EmployeesSeeder::class);
        $this->call(UserActivityLogSeeder::class);
    }
}
