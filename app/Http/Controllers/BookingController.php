<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Booking;
use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * 🔸 แสดงรายการอุปกรณ์ที่พร้อมให้จอง (เฉพาะผู้ใช้ล็อกอิน)
     */
    public function index()
    {
        $equipments = Equipment::where('is_available', true)
            ->where('available', '>', 0)
            ->orderBy('name', 'asc')
            ->get();

        return view('booking.index', compact('equipments'));
    }

    /**
     * 📋 แสดงรายการอุปกรณ์ (สำหรับทุกคน)
     */
    public function equipmentList()
    {
        $equipments = Equipment::orderBy('name', 'asc')->get();
        return view('equipments.index', compact('equipments')); // ✅ ใช้โฟลเดอร์ที่มีจริง
    }

    /**
     * 📝 หน้าแบบฟอร์มจองอุปกรณ์
     */
    public function create()
    {
        $equipments = Equipment::where('is_available', true)->get();
        return view('booking.create', compact('equipments'));
    }

    /**
     * 💾 ฟังก์ชันบันทึกข้อมูลการจอง
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

        // ✅ ตรวจสอบจำนวนอุปกรณ์ที่ว่าง
        if ($request->quantity > $equipment->available) {
            return back()->with('error', '❌ จำนวนที่จองเกินจำนวนที่ว่าง!');
        }

        // ✅ บันทึกข้อมูลการจอง
        Booking::create([
            'user_id'      => Auth::id(),
            'equipment_id' => $equipment->id,
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

        // ✅ อัปเดตจำนวนอุปกรณ์
        $equipment->available -= $request->quantity;
        if ($equipment->available <= 0) {
            $equipment->is_available = false;
        }
        $equipment->save();

        return redirect()->route('booking.index')
            ->with('success', '🎉 จองอุปกรณ์เรียบร้อยแล้ว! กรุณารอการอนุมัติ');
    }

    /**
     * 📦 ผู้ใช้กด "รับของแล้ว"
     */
    public function markAsPickedUp($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === 'approved') {
            $booking->status = 'picked_up';
            $booking->picked_up_at = now();
            $booking->save();
            return back()->with('success', '✅ รับอุปกรณ์เรียบร้อยแล้ว!');
        }

        return back()->with('error', '❌ ไม่สามารถทำเครื่องหมายรับอุปกรณ์ได้');
    }

    /**
     * 📸 ฟังก์ชันคืนอุปกรณ์ (แนบรูป, คิดค่าปรับ)
     */
    public function returnEquipment(Request $request, $id)
    {
        $booking = Booking::with('equipment')->findOrFail($id);
        $today = now();

        if (!in_array($booking->status, ['picked_up', 'overdue'])) {
            return back()->with('error', '❌ ไม่สามารถคืนอุปกรณ์ได้ในสถานะปัจจุบัน');
        }

        // ✅ ตรวจสอบและอัปโหลดรูปภาพ
        $photoPath = null;
        if ($request->hasFile('return_photo')) {
            $request->validate([
                'return_photo' => 'image|mimes:jpeg,png,jpg|max:5120',
            ]);
            $photoPath = $request->file('return_photo')->store('returns', 'public');
        }

        // ✅ ตรวจว่าคืนช้าหรือไม่
        $returnDateTime = Carbon::parse($booking->return_date . ' ' . $booking->return_time);
        $hoursLate = $today->gt($returnDateTime) ? $today->diffInHours($returnDateTime) : 0;
        $daysLate = ceil($hoursLate / 24);

        if ($daysLate > 0) {
            // 💰 มีค่าปรับ
            $fineAmount = $daysLate * 50 * $booking->quantity;
            Fine::create([
                'booking_id' => $booking->id,
                'user_id'    => $booking->user_id,
                'amount'     => $fineAmount,
                'reason'     => "คืนช้า {$daysLate} วัน ({$booking->quantity} ชิ้น)",
                'status'     => 'pending',
            ]);
            $booking->status = 'overdue_user_returned';
        } else {
            $booking->status = 'returned_user_submitted';
        }

        // ✅ อัปเดตข้อมูลการจอง
        $booking->user_returned_at = $today;
        $booking->return_photo = $photoPath;
        $booking->save();

        return back()->with('success', '✅ ส่งหลักฐานคืนอุปกรณ์เรียบร้อยแล้ว! รอตรวจสอบจากเจ้าหน้าที่');
    }

    /**
     * 🧾 แอดมิน/สตาฟตรวจสอบการคืน
     */
    public function returnList()
    {
        $bookings = Booking::with(['user', 'equipment'])
            ->whereIn('status', ['returned_user_submitted', 'overdue_user_returned'])
            ->orderByDesc('user_returned_at')
            ->get();

        return view('booking.return-list', compact('bookings'));
    }

    /**
     * 👤 แสดง "รายการของฉัน"
     */
    public function myPickups()
    {
        $bookings = Booking::with('equipment')
            ->where('user_id', Auth::id())
            ->whereIn('status', [
                'pending',
                'approved',
                'picked_up',
                'returned_user_submitted',
                'overdue_user_returned',
                'returned',
                'overdue',
                'rejected'
            ])
            ->orderBy('borrow_date', 'asc')
            ->get();

        return view('booking.my-pickups', compact('bookings'));
    }
}
