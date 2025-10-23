<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageFineController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * 📜 แสดงรายการค่าปรับทั้งหมด
     */
    public function index(Request $request)
    {
        $this->authorize('manage-bookings');

        $query = Fine::with(['user', 'booking'])->latest();

        // ✅ ตัวกรองสถานะ
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ✅ ค้นหาผู้ใช้
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }

        $fines = $query->paginate(10);

        return view('manage.fines.index', compact('fines'));
    }

    /**
     * 💰 ทำเครื่องหมายว่าชำระแล้ว
     */
    public function markPaid(Fine $fine)
    {
        $this->authorize('manage-bookings');

        if ($fine->status === 'paid') {
            return back()->with('info', 'รายการนี้ชำระเงินแล้ว');
        }

        $fine->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', '💰 บันทึกการชำระค่าปรับเรียบร้อยแล้ว');
    }
}
