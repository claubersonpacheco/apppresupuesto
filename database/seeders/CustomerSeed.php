<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'code' => 'FSCU2025-0001',
            'name' => 'Joaozinho da Silva',
            'email' => 'caubinho@gmail.com',
            'phone' => '+34671151432',
            'document' => 'z1805623J',
            'address' => 'Calle Santo Domingo',
        ]);
    }
}
