<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $users = User::all();

        foreach (range(1, 50) as $i) {
            ActivityLog::create([
                'user_id' => $users->random()->id,
                'action' => collect(['login', 'logout', 'create', 'update', 'delete'])->random(),
                'description' => 'Aktivitas ke-' . $i,
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Test Browser)',
                'created_at' => now()->subMinutes(rand(1, 5000)),
            ]);
        }
    }
}
