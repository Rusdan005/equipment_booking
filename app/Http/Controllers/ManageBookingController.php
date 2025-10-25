<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    // ----------------------------------------------------------------------
    // 📝 พิจารณาการจอง (Review)
    // ----------------------------------------------------------------------
    public function reviewIndex()
    {
        $this->authorize('manage-bookings');
        $bookings = Booking::with(['user', 'equipment'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('manage.bookings.review.index', compact('bookings'));
    }

    public function reviewShow(Booking $booking)
    {
        $this->authorize('manage-bookings');
        $booking->load(['user', 'equipment']);

        return view('manage.bookings.review.show', compact('booking'));
    }

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

    public function reject(Booking $booking, Request $request)
    {
        $this->authorize('manage-bookings');

        $request->validate(['reject_reason' => 'required|string|min:3']);

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
    // 📦 มารับอุปกรณ์ (Pickup)
    // ----------------------------------------------------------------------
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
        return view('manage.bookings.pickup.index', compact('bookings'));
    }

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

    // ----------------------------------------------------------------------
    // 📜 ประวัติการจองทั้งหมด (History)
    // ----------------------------------------------------------------------
    public function historyIndex(Request $request)
    {
        $this->authorize('manage-bookings');

        $query = Booking::with(['user', 'equipment'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', function ($u) use ($request) {
                    $u->where('name', 'like', '%' . $request->search . '%');
                })->orWhereHas('equipment', function ($e) use ($request) {
                    $e->where('name', 'like', '%' . $request->search . '%');
                });
            });
        }

        $bookings = $query->paginate(10);

        return view('manage.bookings.history.index', compact('bookings'));
    }

    // ----------------------------------------------------------------------
    // 📊 ตรวจสอบการคืนอุปกรณ์ (Returns)
    // ----------------------------------------------------------------------
    public function returnsIndex()
    {
        $this->authorize('manage-bookings');

        $bookings = Booking::with(['user', 'equipment'])
            ->whereIn('status', ['returned', 'overdue', 'picked_up'])
            ->orderByDesc('borrow_date')
            ->paginate(10);

        return view('manage.bookings.returns.index', compact('bookings'));
    }

    // ✅ ฟังก์ชันให้แอดมินทำเครื่องหมายว่าคืนแล้ว
    public function markAsReturnedByAdmin($id)
    {
        $booking = Booking::with('equipment')->findOrFail($id);

        $booking->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);

        $booking->equipment->update(['is_available' => true]);

        return back()->with('success', '✅ บันทึกการคืนอุปกรณ์เรียบร้อยแล้ว!');
    }
}
