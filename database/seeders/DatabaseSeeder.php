<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipment;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 🧑‍💻 สร้างผู้ดูแลระบบ
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        // 👤 ผู้ใช้ทั่วไป
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);

        // 📦 อุปกรณ์เริ่มต้น
        Equipment::insert([
            [
                'name' => 'กีฬา',
                'type' => 'กีฬา',
                'description' => 'อุปกรณ์สำหรับเล่นกีฬา',
                'available' => 5,
                'total' => 5,
                'image' => 'bool.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'ห้องประชุม',
                'type' => 'ห้อง',
                'description' => 'ห้องขนาดกลางสำหรับการประชุม',
                'available' => 2,
                'total' => 2,
                'image' => 'room.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'เครื่องครัว',
                'type' => 'เครื่องครัว',
                'description' => 'สำหรับกิจกรรมในห้องอาหาร',
                'available' => 4,
                'total' => 4,
                'image' => 'k.jpg',
                'is_available' => true,
            ],
        ]);
    }
}
