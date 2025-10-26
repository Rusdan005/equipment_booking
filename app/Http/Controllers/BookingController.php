<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Booking;
use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
// ❌ ไม่จำเป็นต้อง use Controller ตัวเอง
// use App\Http\Controllers\BookingController; 

// ❌ Route ต้องย้ายไปไฟล์ routes/web.php
// Route::put('/booking/{id}/picked-up', [BookingController::class, 'markAsPickedUp'])
//     ->name('booking.picked');

class BookingController extends Controller
{
    /**
     * 🔸 หน้าแสดงรายการอุปกรณ์ทั้งหมด
     */
    public function index()
    {
        $equipments = Equipment::where('is_available', true)->get();
        return view('booking.index', compact('equipments'));
    }

    /**
     * 🔸 หน้าแบบฟอร์มจองอุปกรณ์
     */
    public function create()
    {
        $equipments = Equipment::where('is_available', true)->get();
        return view('booking.create', compact('equipments'));
    }

    /**
     * 🔸 ฟังก์ชันบันทึกข้อมูลการจอง
     */
    public function store(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'borrow_date'  => 'required|date|after_or_equal:today',
            'return_date'  => 'required|date|after:borrow_date',
            'pickup_time'  => 'required',
            'return_time'  => 'required',
            'purpose'      => 'required|string|max:255',
            'location'     => 'nullable|string|max:255',
            'major'        => 'nullable|string|max:255',
            'faculty'      => 'nullable|string|max:255',
            'quantity'     => 'required|integer|min:1',
        ]);

        $equipment = Equipment::findOrFail($request->equipment_id);

        // ✅ ตรวจสอบจำนวนที่จองไม่เกินของที่เหลือ
        if ($request->quantity > $equipment->available) {
            return back()->with('error', '❌ จำนวนที่จองเกินจำนวนที่ว่าง!');
        }

        // ✅ บันทึกข้อมูลการจอง
        Booking::create([
            'user_id'      => Auth::id(),
            'equipment_id' => $request->equipment_id,
            'borrow_date'  => $request->borrow_date,
            'return_date'  => $request->return_date,
            'pickup_time'  => $request->pickup_time,
            'return_time'  => $request->return_time,
            'purpose'      => $request->purpose,
            'location'     => $request->location,
            'major'        => $request->major,
            'faculty'      => $request->faculty,
            'quantity'     => $request->quantity,
            'status'       => 'pending',
        ]);

        // ✅ อัปเดตจำนวนอุปกรณ์ที่เหลือ
        $equipment->available = max(0, $equipment->available - $request->quantity);
        if ($equipment->available <= 0) {
            $equipment->is_available = false;
        }
        $equipment->save();

        return redirect()->route('booking.index')
            ->with('success', '🎉 ทำการจองเรียบร้อยแล้ว! กรุณารอการอนุมัติ');
    }

        public function equipmentList()
{
    // ดึงข้อมูลอุปกรณ์ทั้งหมดที่พร้อมให้จอง
    $equipments = \App\Models\Equipment::where('is_available', 1)
        ->orderBy('name', 'asc')
        ->get();

    // ส่งไปยังหน้า view
    return view('booking.equipment-list', compact('equipments'));
}

    /**
     * 🟢 แสดงหน้าตรวจสอบการคืนอุปกรณ์ (สำหรับ Staff/Admin)
     */
    public function returnList()
    {
        $bookings = Booking::with(['user', 'equipment'])
            ->orderBy('borrow_date', 'desc')
            ->get();

        return view('booking.return', compact('bookings'));
    }

    /**
     * ✅ ฟังก์ชันคืนอุปกรณ์ + บันทึกรูป + ปรับสถานะ + คิดค่าปรับ (ใช้ได้ทั้ง user/staff)
     */
    public function returnEquipment(Request $request, $id)
    {
        $booking = Booking::with('equipment')->findOrFail($id);
        $today = now();

        // ✅ ตรวจสอบรูปและบันทึกไฟล์
        if ($request->hasFile('return_photo')) {
            $request->validate([
                'return_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // ลบรูปเก่าถ้ามี
            if ($booking->return_photo && Storage::disk('public')->exists($booking->return_photo)) {
                Storage::disk('public')->delete($booking->return_photo);
            }

            // เก็บรูปใหม่ใน storage/app/public/returns/
            $path = $request->file('return_photo')->store('returns', 'public');
            $booking->return_photo = $path;
        }

        // ✅ ตรวจสอบการคืนช้า
        if ($today->gt($booking->return_date)) {
            $daysLate = $today->diffInDays($booking->return_date);
            $fineAmount = $daysLate * 50; // 💰 50 บาทต่อวัน

            Fine::create([
                'booking_id' => $booking->id,
                'user_id'    => $booking->user_id,
                'amount'     => $fineAmount,
                'reason'     => "คืนช้า {$daysLate} วัน",
                'status'     => 'pending',
            ]);

            $booking->status = 'overdue';
        } else {
            $booking->status = 'returned';
        }

        // ✅ อัปเดตข้อมูลการคืน
        $booking->returned_at = $today;
        $booking->save();

        // ✅ ปล่อยอุปกรณ์กลับมาว่าง
        $booking->equipment->available += $booking->quantity;
        $booking->equipment->is_available = true;
        $booking->equipment->save();

        return redirect()->back()->with('success', '✅ คืนอุปกรณ์และบันทึกรูปเรียบร้อยแล้ว!');
    }

    /**
     * 📦 แสดงหน้ารายการอุปกรณ์ของฉัน
     */
    public function myPickups()
    {
        $bookings = Booking::with('equipment')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved', 'picked_up', 'returned', 'overdue'])
            ->orderBy('borrow_date', 'asc')
            ->get();

        return view('booking.my-pickups', compact('bookings'));
    }

    // ✨✨✨ [เพิ่มใหม่] เมธอดที่ขาดหายไป ✨✨✨
    /**
     * 🚚 อัปเดตสถานะเป็น "รับของแล้ว" (Picked Up)
     */
    public function markAsPickedUp($id)
    {
        $booking = Booking::findOrFail($id);

        // (แนะนำ) คุณอาจต้องการตรวจสอบสิทธิ์ตรงนี้
        // เช่น check ว่าเป็น admin หรือเป็นเจ้าของ booking ที่มีสถานะ 'approved'
        
        // อัปเดตสถานะ
        $booking->status = 'picked_up';
        // $booking->picked_up_at = now(); // (ถ้ามีคอลัมน์นี้ใน DB)
        $booking->save();

        return redirect()->back()->with('success', '🚚 บันทึกการรับอุปกรณ์เรียบร้อยแล้ว');
    }
}