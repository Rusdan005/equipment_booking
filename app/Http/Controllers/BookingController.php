<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Booking;
use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // ✅ ตรวจสอบว่าจำนวนที่จองไม่เกินจำนวนที่ว่าง
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

        // ✅ ปรับจำนวนอุปกรณ์ที่เหลือ
        $equipment->available = max(0, $equipment->available - $request->quantity);

        // ถ้าของหมด → ปิดสถานะให้ยืม
        if ($equipment->available <= 0) {
            $equipment->is_available = false;
        }

        $equipment->save();

        return redirect()->route('booking.index')
            ->with('success', '🎉 ทำการจองเรียบร้อยแล้ว! กรุณารอการอนุมัติ');
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
     * ✅ ฟังก์ชันคืนอุปกรณ์ + บันทึกรูป + คิดค่าปรับ
     */
    public function markAsReturned(Request $request, $id)
    {
        $booking = Booking::with('equipment')->findOrFail($id);
        $today = now();

        // ✅ ตรวจสอบและบันทึกรูปตอนคืน
        $photoPath = null;
        if ($request->hasFile('return_photo')) {
            $request->validate([
                'return_photo' => 'image|mimes:jpeg,png,jpg|max:5120', // สูงสุด 5MB
            ]);
            $photoPath = $request->file('return_photo')->store('returns', 'public');
        }

        // 🔍 ตรวจว่าคืนช้าหรือไม่
        if ($today->gt($booking->return_date)) {
            $daysLate = $today->diffInDays($booking->return_date);
            $fineAmount = $daysLate * 50; // 💰 คิด 50 บาท/วัน

            // ✅ บันทึกค่าปรับ
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

        $booking->returned_at = $today;
        $booking->return_photo = $photoPath;
        $booking->save();

        // ✅ ปล่อยอุปกรณ์กลับมาว่างอีกครั้ง
        $booking->equipment->available += $booking->quantity;
        $booking->equipment->is_available = true;
        $booking->equipment->save();

        return back()->with('success', '✅ คืนอุปกรณ์และบันทึกรูปเรียบร้อยแล้ว!');
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
}
