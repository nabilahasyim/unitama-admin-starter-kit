<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'app_name' => 'Admin Laravel',
            'copyright' => 'Admin Laravel || 2026',
            'login_title' => 'Admin Laravel',
            'keywords' => 'Laravel, Root Admin, Admin Dashboard, Sistem Manajemen, Web Application, PHP, Bootstrap, Manajemen Pengguna, Dashboard Admin',
            'description' => 'Project Root Admin Laravel adalah aplikasi berbasis web yang dibangun menggunakan framework Laravel untuk mengelola data, pengguna, dan berbagai fitur administrasi melalui dashboard yang terstruktur, aman, dan mudah digunakan',
        ]);
    }
}
