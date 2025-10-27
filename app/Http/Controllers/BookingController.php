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
     * 🔸 หน้าแสดงรายการอุปกรณ์ทั้งหมด (สำหรับผู้ใช้ที่ล็อกอินแล้ว)
     */
    public function index()
    {
        // 💡 ปรับให้เลือกเฉพาะอุปกรณ์ที่ is_available เป็น true และมี available > 0
        $equipments = Equipment::where('is_available', true)
                                ->where('available', '>', 0)
                                ->get();
        return view('booking.index', compact('equipments'));
    }

    /**
     * 📋 หน้ารายการอุปกรณ์ (ทุกคนดูได้ - Route public)
     */
    public function equipmentList()
    {
        $equipments = Equipment::all(); // แสดงทั้งหมด ไม่ว่าว่างหรือไม่
        return view('equipment.index', compact('equipments'));
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
            'status'       => 'pending', // สถานะเริ่มต้น
        ]);

        // 💡 ลด 'available' ทันทีเพื่อป้องกันการจองซ้ำซ้อน
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
     * ✅ ทำเครื่องหมายว่า "รับของแล้ว" (ผู้ใช้แจ้งรับ)
     */
    public function markAsPickedUp($id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->status === 'approved') {
            $booking->status = 'picked_up';
            $booking->picked_up_at = now();
            $booking->save();
            return back()->with('success', '✅ ทำเครื่องหมายว่าได้รับอุปกรณ์เรียบร้อยแล้ว!');
        }
        
        return back()->with('error', '❌ ไม่สามารถทำเครื่องหมายรับอุปกรณ์ได้ในสถานะปัจจุบัน');
    }


    /**
     * ✅ ฟังก์ชันคืนอุปกรณ์ + บันทึกรูป + คิดค่าปรับ (ผู้ใช้ส่งฟอร์มคืน)
     */
    public function returnEquipment(Request $request, $id)
    {
        $booking = Booking::with('equipment')->findOrFail($id);
        $today = now();

        if ($booking->status !== 'picked_up' && $booking->status !== 'overdue') {
            return back()->with('error', '❌ ไม่สามารถคืนอุปกรณ์ได้ในสถานะปัจจุบัน');
        }

        // 1. ✅ ตรวจสอบและบันทึกรูปตอนคืน
        $photoPath = null;
        if ($request->hasFile('return_photo')) {
            $request->validate([
                'return_photo' => 'image|mimes:jpeg,png,jpg|max:5120', // สูงสุด 5MB
            ]);
            $photoPath = $request->file('return_photo')->store('returns', 'public');
        }

        // 2. 🔍 ตรวจว่าคืนช้าหรือไม่
        $returnDateTime = Carbon::parse($booking->return_date . ' ' . $booking->return_time);
        
        if ($today->gt($returnDateTime)) {
            
            // 💡 ปรับปรุงตรรกะการคำนวณวันล่าช้าให้แม่นยำขึ้น
            $hoursLate = $today->diffInHours($returnDateTime);
            
            // 💰 คำนวณวันล่าช้า: หากเกิน 24 ชม. นับเป็น 1 วันเต็ม, เกิน 48 ชม. เป็น 2 วัน ฯลฯ
            // ใช้ ceil() เพื่อปัดเศษขึ้น: ล่าช้า 1 ชม. ก็คือ 1 วัน
            $daysLate = (int) ceil($hoursLate / 24);

            if ($daysLate > 0) {
                $fineAmount = $daysLate * 50 * $booking->quantity; // 💰 คิด 50 บาท/วัน/ชิ้น
                
                // ✅ บันทึกค่าปรับ
                Fine::create([
                    'booking_id' => $booking->id,
                    'user_id'    => $booking->user_id,
                    'amount'     => $fineAmount,
                    'reason'     => "คืนช้า {$daysLate} วัน ({$booking->quantity} ชิ้น)",
                    'status'     => 'pending',
                ]);
                
                $booking->status = 'overdue_user_returned'; // สถานะใหม่: ผู้ใช้คืนแล้ว (ล่าช้า)
            } else {
                $booking->status = 'returned_user_submitted'; // สถานะใหม่: ผู้ใช้คืนแล้ว (ตรงเวลา/ก่อน)
            }
        } else {
            $booking->status = 'returned_user_submitted'; // ผู้ใช้ยืนยันการคืน ไม่มีค่าปรับ
        }

        // 3. ✅ อัปเดตข้อมูลการจอง
        $booking->user_returned_at = $today; // บันทึกวันที่ผู้ใช้กดคืน
        $booking->return_photo = $photoPath;
        $booking->save();
        
        return back()->with('success', '✅ ส่งคำขอคืนอุปกรณ์และบันทึกรูปเรียบร้อยแล้ว! กรุณารอเจ้าหน้าที่ตรวจสอบ');
    }

    /**
     * 🟢 แสดงหน้าตรวจสอบการคืนอุปกรณ์ (สำหรับ Staff/Admin)
     */
    public function returnList()
    {
        // 💡 แสดงรายการที่ผู้ใช้แจ้งคืนแล้วและรอการตรวจสอบ
        $bookings = Booking::with(['user', 'equipment'])
            ->whereIn('status', ['returned_user_submitted', 'overdue_user_returned'])
            // ✅ ใช้คอลัมน์ user_returned_at ที่คาดว่าคุณได้เพิ่มใน migration แล้ว
            ->orderBy('user_returned_at', 'desc') 
            ->get();

        return view('booking.return-list', compact('bookings')); 
    }

    /**
     * 📦 แสดงหน้ารายการอุปกรณ์ของฉัน (My Pickups)
     */
    public function myPickups()
    {
        $bookings = Booking::with('equipment')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved', 'picked_up', 'returned_user_submitted', 'overdue_user_returned', 'returned', 'overdue', 'rejected'])
            ->orderBy('borrow_date', 'asc')
            ->get();

        return view('booking.my-pickups', compact('bookings'));
    }
}