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
        // 🎾 หมวดกีฬา
            [
                'name' => 'บาสเกตบอล',
                'type' => 'กีฬา',
                'description' => 'ลูกบาสเกตบอลสำหรับฝึกซ้อมและแข่งขัน',
                'available' => 5,
                'total' => 5,
                'image' => 'bas.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'แบดมินตัน',
                'type' => 'กีฬา',
                'description' => 'ไม้แบดและลูกขนไก่พร้อมใช้งาน',
                'available' => 6,
                'total' => 6,
                'image' => 'bat.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'ฟุตบอล',
                'type' => 'กีฬา',
                'description' => 'ลูกฟุตบอลหนังแท้ขนาดมาตรฐาน',
                'available' => 4,
                'total' => 4,
                'image' => 'foot.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'แฮนด์บอล',
                'type' => 'กีฬา',
                'description' => 'ลูกแฮนด์บอลสำหรับการเรียนและแข่งขัน',
                'available' => 3,
                'total' => 3,
                'image' => 'hand.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'ปิงปอง',
                'type' => 'กีฬา',
                'description' => 'ไม้ปิงปองพร้อมลูกปิงปองครบชุด',
                'available' => 8,
                'total' => 8,
                'image' => 'pin.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'ตะกร้อ',
                'type' => 'กีฬา',
                'description' => 'ลูกตะกร้อและตาข่ายสำหรับฝึกซ้อม',
                'available' => 6,
                'total' => 6,
                'image' => 'tak.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'วอลเลย์บอล',
                'type' => 'กีฬา',
                'description' => 'ลูกวอลเลย์บอลมาตรฐานแข่งขัน',
                'available' => 5,
                'total' => 5,
                'image' => 'wow.jpg',
                'is_available' => true,
            ],

            // ⚡ หมวดอุปกรณ์ทั่วไป
            [
                'name' => 'ปลั๊กไฟ',
                'type' => 'อุปกรณ์ไฟฟ้า',
                'description' => 'ปลั๊กไฟต่อพ่วงคุณภาพดี 5 ช่อง',
                'available' => 10,
                'total' => 10,
                'image' => 'power.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'โปรเจคเตอร์',
                'type' => 'อุปกรณ์ไฟฟ้า',
                'description' => 'เครื่องโปรเจคเตอร์สำหรับห้องประชุมหรือกิจกรรมต่าง ๆ',
                'available' => 2,
                'total' => 2,
                'image' => 'pro.jpg',
                'is_available' => true,
            ],
        ]);
    }
}