<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Role::create([
            'role_name' => 'superadmin',
        ]);

        Role::create([
            'role_name' => 'dekanatadmin',
        ]);

        Role::create([
            'role_name' => 'prodiadmin',
        ]);

        Role::create([
            'role_name' => 'dosen',
        ]);

        Role::create([
            'role_name' => 'pegawai1',
        ]);

        Role::create([
            'role_name' => 'pegawai2',
        ]);

        User::factory(5)->create();

    }
}
