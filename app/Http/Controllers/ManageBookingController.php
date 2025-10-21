<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageBookingController extends Controller
{
    /**
     * ✅ กำหนดให้ต้องล็อกอินก่อนใช้งานทุกฟังก์ชัน
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    // ----------------------------------------------------------------------
    // 📝 ส่วนที่ 1 : พิจารณาการจอง (Review)
    // ----------------------------------------------------------------------

    /**
     * แสดงรายการคำขอที่รอพิจารณา (status = pending)
     */
    public function reviewIndex()
    {
        $this->authorize('manage-bookings'); // ตรวจสิทธิ์

        $bookings = Booking::with(['user', 'equipment'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('bookings.review-index', compact('bookings'));
    }

    /**
     * แสดงรายละเอียดคำขอแต่ละรายการ
     */
    public function reviewShow(Booking $booking)
    {
        $this->authorize('manage-bookings');

        $booking->load(['user', 'equipment']);
        return view('bookings.review-show', compact('booking'));
    }

    /**
     * อนุมัติคำขอ
     */
    public function approve(Booking $booking)
    {
        $this->authorize('manage-bookings');

        if ($booking->status !== 'pending') {
            return back()->with('error', 'คำขอนี้ถูกพิจารณาไปแล้ว');
        }

        $booking->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'pickup_code' => $booking->pickup_code ?? strtoupper(str()->random(6)),
        ]);

        return redirect()->route('manage.bookings.review.index')
            ->with('success', '✅ อนุมัติคำขอเรียบร้อยแล้ว');
    }

    /**
     * ปฏิเสธคำขอ
     */
    public function reject(Booking $booking, Request $request)
    {
        $this->authorize('manage-bookings');

        $request->validate([
            'reject_reason' => 'required|string|min:3'
        ]);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'คำขอนี้ถูกพิจารณาไปแล้ว');
        }

        $booking->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'reject_reason' => $request->reject_reason,
        ]);

        return redirect()->route('manage.bookings.review.index')
            ->with('success', '❌ ปฏิเสธคำขอเรียบร้อยแล้ว');
    }

    // ----------------------------------------------------------------------
    // 📦 ส่วนที่ 2 : มารับอุปกรณ์ (Pickup)
    // ----------------------------------------------------------------------

    /**
     * แสดงรายการคำขอที่อนุมัติแล้ว แต่ยังไม่มารับ
     */
    public function pickupIndex(Request $request)
    {
        $this->authorize('manage-bookings');

        $query = Booking::with(['user', 'equipment'])
            ->where('status', 'approved')
            ->whereNull('picked_up_at')
            ->latest();

        if ($request->filled('code')) {
            $query->where('pickup_code', $request->code);
        }

        $bookings = $query->paginate(10);

        return view('bookings.pickup-index', compact('bookings'));
    }

    /**
     * ทำเครื่องหมายว่ารับอุปกรณ์แล้ว
     */
    public function pickup(Booking $booking)
    {
        $this->authorize('manage-bookings');

        if ($booking->status !== 'approved') {
            return back()->with('error', 'คำขอนี้ไม่สามารถรับอุปกรณ์ได้');
        }

        $booking->update([
            'status' => 'picked_up',
            'picked_up_by' => Auth::id(),
            'picked_up_at' => now(),
        ]);

        return redirect()->route('manage.bookings.pickup.index')
            ->with('success', '📦 บันทึกการรับอุปกรณ์เรียบร้อยแล้ว');
    }
}
