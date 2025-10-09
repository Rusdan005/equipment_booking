<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ตัวอย่าง factory ที่มีอยู่เดิม
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // เรียกใช้ Seeder อื่น ๆ ที่ต้องการ
        $this->call(\Database\Seeders\AdminUserSeeder::class);
    }
}
