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
        $equipments = Equipment::where('is_available', true)->get();
        return view('booking.index', compact('equipments'));
    }

    // 🔸 หน้าแบบฟอร์มจองอุปกรณ์
    public function create()
    {
        $equipments = Equipment::where('is_available', true)->get();
        return view('booking.create', compact('equipments'));
    }

    // 🔸 ฟังก์ชันบันทึกข้อมูลการจอง
    public function store(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:borrow_date',
            'purpose' => 'required|string|max:255',
        ]);

        Booking::create([
            'user_id' => Auth::id(),
            'equipment_id' => $request->equipment_id,
            'borrow_date' => $request->borrow_date,
            'return_date' => $request->return_date,
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);

        Equipment::where('id', $request->equipment_id)->update(['is_available' => false]);

        return redirect()->route('booking.index')->with('success', '🎉 ทำการจองเรียบร้อยแล้ว!');
    }

    // 🟢 แสดงหน้าตรวจสอบการคืนอุปกรณ์
    public function returnList()
    {
        $bookings = Booking::with(['user', 'equipment'])
            ->orderBy('borrow_date', 'desc')
            ->get();

        return view('booking.return', compact('bookings'));
    }

    // 🟢 อัปเดตสถานะเมื่อคืนอุปกรณ์แล้ว
    public function markAsReturned($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'returned';
        $booking->save();

        // ✅ ปล่อยให้อุปกรณ์กลับมา “ว่าง” อีกครั้ง
        $booking->equipment->update(['is_available' => true]);

        return back()->with('success', '✅ อุปกรณ์ถูกทำเครื่องหมายว่าคืนแล้ว!');
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
