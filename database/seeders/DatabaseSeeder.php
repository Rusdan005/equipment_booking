<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🔹 สร้างบัญชีผู้ดูแลระบบ (Admin)
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        // 🔹 สร้างบัญชีผู้ใช้ทดสอบ
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);

        // 🔹 เพิ่มข้อมูลอุปกรณ์เริ่มต้นที่สามารถจองได้
        Equipment::insert([
            ['name' => 'โปรเจคเตอร์ Epson X300', 'type' => 'อุปกรณ์ภาพ', 'is_available' => true],
            ['name' => 'ไมค์ไร้สาย Shure', 'type' => 'อุปกรณ์เสียง', 'is_available' => true],
            ['name' => 'กล้อง DSLR Canon', 'type' => 'กล้อง', 'is_available' => true],
            ['name' => 'ขาตั้งกล้อง', 'type' => 'อุปกรณ์เสริม', 'is_available' => true],
            ['name' => 'โน้ตบุ๊ก Asus', 'type' => 'คอมพิวเตอร์', 'is_available' => true],
        ]);
    }
}
