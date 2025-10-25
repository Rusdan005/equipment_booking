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
                'name' => 'อุปกรณ์กีฬา',
                'type' => 'กีฬา',
                'description' => 'อุปกรณ์สำหรับเล่นกีฬา เช่น ลูกบอลและอุปกรณ์ฝึกซ้อม',
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
    }
}