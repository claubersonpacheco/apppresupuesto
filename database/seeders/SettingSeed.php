<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'title' => 'Fercheco Solutions',
            'logo' => '',
            'favicon' => '',
            'send_email' => '',
            'whatsapp' => '+34671151432',
            'prefix' => 'FS'
            ]);
    }
}
