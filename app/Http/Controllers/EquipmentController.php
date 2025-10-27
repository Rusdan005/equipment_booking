<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EquipmentController extends Controller
{
    /**
     * 1. READ (Index)
     * แสดงหน้ารายการอุปกรณ์ทั้งหมด
     */
    public function index()
    {
        // ดึงข้อมูลอุปกรณ์ล่าสุด โดยแบ่งหน้า (Paginate)
        $equipments = Equipment::latest()->paginate(10);
        
        // [แก้ไข] เปลี่ยนพาธ view ให้ถูกต้อง
        return view('manage.equipments.index', compact('equipments'));
    }

    /**
     * 2. CREATE (Form)
     * แสดงหน้าฟอร์มสำหรับสร้างอุปกรณ์ใหม่
     */
    public function create()
    {
        // [แก้ไข] เปลี่ยนพาธ view ให้ถูกต้อง
        return view('manage.equipments.create');
    }

    /*
     * [ลบ] ฟังก์ชัน create() ที่ซ้ำซ้อนและผิดพลาด
     */

    /**
     * 3. STORE (Save)
     * บันทึกข้อมูลอุปกรณ์ใหม่ลงฐานข้อมูล
     */
    public function store(Request $request)
    {
        // 3.1 ตรวจสอบความถูกต้องของข้อมูล (Validation)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:equipments', // รหัสอุปกรณ์ต้องไม่ซ้ำ
            'total' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // รูปภาพ (ถ้ามี)
        ]);

        // 3.2 จัดการการอัปโหลดรูปภาพ (ถ้ามี)
        if ($request->hasFile('image')) {
            // บันทึกไฟล์ลงใน 'storage/app/public/equipment_images'
            // และเก็บเฉพาะ 'equipment_images/filename.jpg' ลง $path
            $path = $request->file('image')->store('equipment_images', 'public');
            $validated['image'] = $path;
        }

        // 3.3 เมื่อสร้างใหม่ ให้จำนวน 'available' (ที่ยืมได้) = 'total' (ทั้งหมด)
        $validated['available'] = $validated['total'];

        // 3.4 สร้างข้อมูลใหม่ลงตาราง
        Equipment::create($validated);

        // 3.5 กลับไปหน้ารายการ พร้อมข้อความ "success"
        return Redirect::route('equipments.index')->with('success', 'เพิ่มอุปกรณ์ใหม่เรียบร้อยแล้ว');
    }

    /**
     * 4. SHOW
     * (สำหรับ Resource นี้ เราจะข้ามไปใช้ Edit)
     */
    public function show(Equipment $equipment)
    {
        // ส่งต่อไปหน้า edit เลย เพราะใน admin panel เรามักจะ "ดู" = "แก้ไข"
        return redirect()->route('equipments.edit', $equipment);
    }

    /**
     * 5. EDIT (Form)
     * แสดงหน้าฟอร์มสำหรับแก้ไขข้อมูล โดยดึงข้อมูลเก่ามาแสดง
     */
    public function edit(Equipment $equipment)
    {
        // [แก้ไข] เปลี่ยนพาธ view ให้ถูกต้อง
        return view('manage.equipments.edit', compact('equipment'));
    }

    /**
     * 6. UPDATE (Save)
     * อัปเดตข้อมูลที่แก้ไขลงฐานข้อมูล
     */
    public function update(Request $request, Equipment $equipment)
    {
        // 6.1 ตรวจสอบความถูกต้องของข้อมูล (Validation)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            // รหัสอุปกรณ์ต้องไม่ซ้ำ (ยกเว้นรหัสของตัวเอง)
            'serial_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('equipments')->ignore($equipment->id),
            ],
            'total' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 6.2 จัดการการอัปโหลดรูปภาพใหม่ (ถ้ามี)
        if ($request->hasFile('image')) {
            // 1. ลบรูปเก่า (ถ้ามี)
            if ($equipment->image) {
                Storage::disk('public')->delete($equipment->image);
            }
            // 2. อัปโหลดรูปใหม่
            $path = $request->file('image')->store('equipment_images', 'public');
            $validated['image'] = $path;
        }

        // 6.3 คำนวณ 'available' ใหม่
        // หาส่วนต่างของ 'total' เก่า กับ 'total' ใหม่
        $totalDifference = $validated['total'] - $equipment->total;
        
        // เอาส่วนต่างไปบวก/ลบ กับ 'available' เดิม
        // และต้องมั่นใจว่า available ไม่ติดลบ
        $validated['available'] = max(0, $equipment->available + $totalDifference);

        // 6.4 อัปเดตข้อมูล
        $equipment->update($validated);

        // 6.5 กลับไปหน้ารายการ พร้อมข้อความ "success"
        return Redirect::route('equipments.index')->with('success', 'อัปเดตข้อมูลอุปกรณ์เรียบร้อยแล้ว');
    }

    /**
     * 7. DESTROY (Delete)
     * ลบข้อมูลอุปกรณ์
     */
    public function destroy(Equipment $equipment)
    {
        // (เพิ่ม) ตรวจสอบเงื่อนไขก่อนลบ เช่น ห้ามลบถ้ายังมีคนยืมอยู่ (available != total)
        if ($equipment->available < $equipment->total) {
            return Redirect::route('equipments.index')->with('error', 'ไม่สามารถลบอุปกรณ์ได้ เนื่องจากยังมีผู้ยืมอยู่');
        }

        // 1. ลบรูปภาพออกจาก storage (ถ้ามี)
        if ($equipment->image) {
            Storage::disk('public')->delete($equipment->image);
        }

        // 2. ลบข้อมูลออกจากฐานข้อมูล
        $equipment->delete();

        // 3. กลับไปหน้ารายการ พร้อมข้อความ "success"
        return Redirect::route('equipments.index')->with('success', 'ลบอุปกรณ์เรียบร้อยแล้ว');
    }
}

