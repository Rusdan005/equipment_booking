<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipment;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        Equipment::insert([
            [
                'name' => 'Canon EOS R5',
                'type' => 'กล้อง',
                'description' => 'กล้องระดับมืออาชีพ ความละเอียดสูง',
                'available' => 3,
                'total' => 3,
                'image' => 'camera.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'DJI Mavic 3',
                'type' => 'โดรน',
                'description' => 'โดรนพร้อมกล้อง Hasselblad',
                'available' => 2,
                'total' => 2,
                'image' => 'drone.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'iPad Pro 12.9"',
                'type' => 'แท็บเล็ต',
                'description' => 'M2 chip, 256GB, รองรับ Apple Pencil',
                'available' => 5,
                'total' => 5,
                'image' => 'ipad.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'MacBook Pro 16"',
                'type' => 'คอมพิวเตอร์',
                'description' => 'M3 Max, 36GB RAM, 1TB SSD',
                'available' => 4,
                'total' => 4,
                'image' => 'macbook.jpg',
                'is_available' => true,
            ],
        ]);
    }
}
