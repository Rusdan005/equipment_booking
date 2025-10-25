<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipment;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 🧑‍💻 สร้างผู้ดูแลระบบ (Admin)
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password1234'),
        ]);

        // 👤 ผู้ใช้ทั่วไป (User)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'role' => 'user',
            'password' => bcrypt('password1234'),
        ]);

        // 📦 เพิ่มข้อมูลอุปกรณ์เริ่มต้น
        Equipment::insert([
            [
                'name' => 'อุปกรณ์กีฬา',
                'type' => 'กีฬา',
                'description' => 'อุปกรณ์สำหรับเล่นกีฬา เช่น ลูกบอล และอุปกรณ์ฝึกซ้อม',
                'available' => 5,
                'total' => 5,
                'image' => 'bool.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'ห้องประชุม',
                'type' => 'ห้อง',
                'description' => 'ห้องประชุมขนาดกลาง พร้อมโต๊ะและโปรเจคเตอร์',
                'available' => 2,
                'total' => 2,
                'image' => 'room.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'เครื่องครัว',
                'type' => 'เครื่องครัว',
                'description' => 'อุปกรณ์สำหรับกิจกรรมห้องอาหารและงานบริการ',
                'available' => 4,
                'total' => 4,
                'image' => 'k.jpg',
                'is_available' => true,
            ],
        ]);

        // ✅ แจ้งเตือนใน console (optional)
        $this->command->info('✅ Database seeding completed successfully!');
    }
}
