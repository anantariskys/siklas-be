<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = ['PTI', 'SI', 'TI', 'TIF', 'TEKKOM'];
        $roles = ['mahasiswa', 'admin', 'kaprodi', 'dosen'];

        // Seeding users for each program and role combination
        foreach ($programs as $program) {
            foreach ($roles as $role) {
                User::updateOrCreate(
                    ['username' => strtolower($role) . '_' . strtolower($program)],
                    [
                        'name' => ucfirst($role) . ' ' . $program,
                        'email' => strtolower($role) . '.' . strtolower($program) . '@siklas.test',
                        'password' => Hash::make('password'),
                        'role' => $role,
                        'program_studi' => $program,
                    ]
                );
            }
        }

        // Additional admin without specific program
        User::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@siklas.test',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'program_studi' => 'NULL',
            ]
        );
    }
}
