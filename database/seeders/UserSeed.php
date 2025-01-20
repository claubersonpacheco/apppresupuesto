<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'code' => 'FSUS2025-0001',
            'name' => 'Webmaster',
            'email' => 'caubinho@gmail.com',
            'password' => bcrypt('cau12345'),
        ]);
    }
}
