<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(BidangPenelitanSeeder::class);
        $this->call(DosenSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(RiwayatKlasifikasiSeeder::class);

        // for ($i = 1; $i <= 50; $i++) {
        //     User::factory()->create([
        //         'name'          => 'Mahasiswa ' . $i,
        //         'username'      => 'mahasiswa' . $i,
        //         'email'         => 'mahasiswa' . $i . '@siklas.test',
        //         'email_verified_at' => now(),
        //         'role'          => 'mahasiswa',
        //         'password'      => bcrypt('password'),
        //     ]);
        // }

        $users = [
            [
                'name'          => 'Admin User',
                'username'      => 'admin',
                'email'         => 'admin@siklas.test',
                'role'          => 'admin',
            ],
            [
                'name'          => 'Ir. Satrio Hadi Wijoyo, S.Si., S.Pd., M.Kom.',
                'username'      => 'satriohadi',
                'email'         => 'satriohadi@ub.ac.id',
                'role'          => 'dosen',
                'program_studi' => 'PTI',
            ],
            [
                'name'          => 'Ir. Aditya Rachmadi, S.ST., M.TI.',
                'username'      => 'adityarachmadi',
                'email'         => 'rachmadi.aditya@ub.ac.id',
                'role'          => 'dosen',
                'program_studi' => 'PTI',

            ],
            [
                'name'          => 'Ananta Risky Susanto',
                'username'      => '225150601111013',
                'email'         => 'anantariskys@student.ub.ac.id',
                'role'          => 'mahasiswa',
                 'program_studi' => 'PTI',

            ],
            [
                'name'          => 'Alif Nur Syanubari',
                'username'      => '225150600111020',
                'email'         => 'alifnurs@student.ub.ac.id',
                'role'          => 'mahasiswa',
                 'program_studi' => 'PTI',

            ],
            [
                'name'          => 'Muhammad Ahsan Furqan',
                'username'      => '225150607111033',
                'email'         => 'ahsanfurkab@student.ub.ac.id',
                'role'          => 'mahasiswa',
                 'program_studi' => 'PTI',

            ],
            [
                'name'          => 'Ikhlasul Amal',
                'username'      => '225150607111018',
                'email'         => 'ikhlasulamal695@student.ub.ac.id',
                'role'          => 'mahasiswa',
                 'program_studi' => 'PTI',

            ],
            

          
        ];

        foreach ($users as $user) {
            User::factory()->create(array_merge($user, [
                'email_verified_at' => now(),
                'password'          => bcrypt('password'),
            ]));
        }
    }
}
