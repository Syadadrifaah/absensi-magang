<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = User::where('role_id', '!=', 1)->get(); 
        // role_id 1 misalnya admin → tidak masuk employees

        $nipBase = 20260001;

        foreach ($users as $index => $user) {

            Employee::create([
                'user_id'      => $user->id,
                'kategori_id'  => rand(1, 3),   // sesuaikan jumlah kategori
                'position_id'  => rand(1, 5),   // sesuaikan jumlah jabatan
                'nip'          => $nipBase + $index,
                'name'         => $user->name,
                'department'   => $this->randomDepartment(),
                'email'        => $user->email,
                'phone'        => '08' . rand(1111111111, 9999999999),
            ]);
        }

        
    }

    
    private function randomDepartment(): string
        {
            $departments = [
                'HRD',
                'Keuangan',
                'IT',
                'Operasional',
                'Marketing',
            ];

            return $departments[array_rand($departments)];
        }
}
