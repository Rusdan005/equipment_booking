<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // 🔸 หน้าแสดงรายการอุปกรณ์ทั้งหมด
    public function index()
    {
        // ดึงเฉพาะอุปกรณ์ที่ยังว่าง
        $equipments = Equipment::where('is_available', true)->get();
        return view('booking.index', compact('equipments'));
    }

    // 🔸 หน้าแบบฟอร์มจองอุปกรณ์ (แก้รวมให้ถูก)
    public function create()
    {
        // ดึงข้อมูลอุปกรณ์ทั้งหมด (หรือเฉพาะที่ยังว่าง)
        $equipments = Equipment::where('is_available', true)->get();

        // ส่งไปยังหน้า booking.create
        return view('booking.create', compact('equipments'));
    }

    // 🔸 บันทึกข้อมูลการจอง
    public function store(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:borrow_date',
            'usage_type' => 'required|string',
            'location' => 'required|string',
            'purpose' => 'required|string',
        ]);

        Booking::create([
            'user_id' => Auth::id(),
            'equipment_id' => $request->equipment_id,
            'borrow_date' => $request->borrow_date,
            'return_date' => $request->return_date,
            'usage_type' => $request->usage_type,
            'location' => $request->location,
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);

        return redirect()->route('booking.index')->with('success', 'ทำการจองเรียบร้อยแล้ว!');
    }

    // 📅 🆕 หน้ากำหนดรับอุปกรณ์ของฉัน (User เห็นเอง)
    public function myPickups()
    {
        // ดึงรายการของผู้ใช้เอง ที่อนุมัติแล้ว และยังไม่รับ
        $bookings = Booking::query()
            ->where('user_id', Auth::id())
            ->where('status', 'approved')
            ->whereNull('picked_up_at')
            ->orderBy('borrow_date', 'asc')
            ->get();

        // ส่งข้อมูลไปหน้า bookings/my-pickups.blade.php
        return view('booking.my-pickups', compact('bookings'));
    }
}
