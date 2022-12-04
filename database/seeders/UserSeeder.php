<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Arslan',
            'username' => 'arslan99',
            'email' => 'narlyyev0999@gmail.com',
            'password' => bcrypt('Felix12@'),
            'is_admin' => 1,
        ]);
    }
}
