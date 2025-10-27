<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ManageBookingController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\ManageFineController;
use App\Http\Controllers\EquipmentController; // 👈 1. [มีอยู่แล้ว]

// ❌ [ลบออก] Route::resource('equipments', ...) ที่อยู่ผิดที่ ถูกย้ายไปกลุ่ม admin แล้ว

/*
|--------------------------------------------------------------------------
| 🌐 Public Routes (ไม่ต้องล็อกอิน)
|--------------------------------------------------------------------------
*/

// 🏠 หน้าแรก
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 📋 หน้ารายการอุปกรณ์ (ทุกคนดูได้)
Route::get('/equipment', [BookingController::class, 'equipmentList'])
    ->name('equipment.index');

/*
|--------------------------------------------------------------------------
| 🎒 Booking & Profile Routes (ต้องล็อกอิน)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // ✅ แสดงรายการอุปกรณ์ที่ว่างให้จอง
    Route::get('/booking', [BookingController::class, 'index'])
        ->name('booking.index');

    // ✅ ฟอร์มจองอุปกรณ์
    Route::get('/booking/create', [BookingController::class, 'create'])
        ->name('booking.create');

    // ✅ บันทึกข้อมูลการจอง
    Route::post('/booking/store', [BookingController::class, 'store'])
        ->name('booking.store');

    // ✅ ฟังก์ชันการคืนอุปกรณ์ (ผู้ใช้ส่งฟอร์มคืน)
    Route::put('/booking/{id}/return', [BookingController::class, 'returnEquipment'])
        ->name('booking.return');

    // ✅ ทำเครื่องหมายว่า "รับของแล้ว"
    Route::put('/booking/{id}/picked-up', [BookingController::class, 'markAsPickedUp'])
        ->name('booking.picked');

    // 👤 โปรไฟล์ผู้ใช้
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| 🎯 Routes สำหรับผู้ใช้ทั่วไป (User)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->group(function () {
    // ✅ หน้า “กำหนดรับอุปกรณ์ของฉัน” + คืนอุปกรณ์ (พร้อมอัปโหลดรูป)
    Route::get('/pickups/mine', [BookingController::class, 'myPickups'])
        ->name('pickups.mine');
});

/*
|--------------------------------------------------------------------------
| 🧩 Admin / Staff Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,staff'])->group(function () {

    // 🧑‍💼 แดชบอร์ดแอดมิน
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    // ⚙️ จัดการข้อมูลพื้นฐาน
    Route::get('/manage/masterdata', [MasterDataController::class, 'index'])
        ->name('manage.masterdata.index');

    // ✨ [แก้ไข] 🧩 จัดการอุปกรณ์ (เพิ่ม/แก้/ลบ/ดู)
    // นี่คือบรรทัดที่ถูกต้อง: สร้าง 7 routes (เช่น /equipments, /equipments/create)
    // และชี้ไปที่ EquipmentController (ตัวใหม่)
    Route::resource('equipments', EquipmentController::class);

    // ❌ [ลบออก] โค้ดเก่าที่ชี้ไป ManageBookingController
    /*
    Route::prefix('equipment')->name('equipment.')->group(function () {
        Route::get('/create', [ManageBookingController::class, 'create'])->name('create');
        Route::post('/store', [ManageBookingController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ManageBookingController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [ManageBookingController::class, 'update'])->name('update');
        Route::delete('/{id}', [ManageBookingController::class, 'destroy'])->name('destroy');
    });
    */

    // ✨ [ย้ายมา] ✅ หน้า "รายการการคืนอุปกรณ์ทั้งหมด" สำหรับ Staff/Admin
    Route::get('/booking/return-list', [BookingController::class, 'returnList'])
        ->name('booking.return.list');

    // 📝 การจัดการการจอง (ที่เหลือ)
    Route::prefix('manage/bookings')->name('manage.bookings.')->group(function () {
        Route::get('review', [ManageBookingController::class, 'reviewIndex'])->name('review.index');
        Route::get('review/{booking}', [ManageBookingController::class, 'reviewShow'])->name('review.show');
        Route::post('review/{booking}/approve', [ManageBookingController::class, 'approve'])->name('review.approve');
        Route::post('review/{booking}/reject', [ManageBookingController::class, 'reject'])->name('review.reject');
        Route::get('pickup', [ManageBookingController::class, 'pickupIndex'])->name('pickup.index');
        Route::post('pickup/{booking}', [ManageBookingController::class, 'pickup'])->name('pickup.do');
        Route::get('history', [ManageBookingController::class, 'historyIndex'])->name('history.index');
        Route::get('returns', [ManageBookingController::class, 'returnsIndex'])->name('returns.index');
        // 🚨 ถูกต้อง: Route สำหรับ Staff/Admin ทำเครื่องหมายว่าคืนแล้ว
        Route::put('returns/{id}', [ManageBookingController::class, 'markAsReturnedByAdmin'])->name('returns.mark');
    });

    // 💰 การจัดการค่าปรับ
    Route::prefix('manage/fines')->name('manage.fines.')->group(function () {
        Route::get('/', [ManageFineController::class, 'index'])->name('index');
        Route::post('/{fine}/mark-paid', [ManageFineController::class, 'markPaid'])->name('markPaid');
    });

    // 🧾 หน้าใหม่: ดูรูปตอนคืน + รายละเอียดการคืนทั้งหมด
    Route::get('/manage/returns/photos', [ManageBookingController::class, 'viewReturnPhotos'])
        ->name('manage.returns.photos');
});

/*
|--------------------------------------------------------------------------
| 📊 Dashboard & Auth
|--------------------------------------------------------------------------
*/

// ✅ หน้า Dashboard หลังล็อกอิน
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 🔐 ระบบ Auth (Login / Register / Forgot Password)
require __DIR__ . '/auth.php';

// ❌ ลบส่วนนี้ออก: ฟังก์ชันนี้ควรอยู่ใน ManageBookingController หรือ BookingController เท่านั้น
/*
public function markReturned($id)
{
    $booking = Booking::findOrFail($id);
    $booking->status = 'returned';
    $booking->return_time = now();
    $booking->save();

    return redirect()->route('booking.return.list')
        ->with('success', 'ทำเครื่องหมายคืนอุปกรณ์เรียบร้อยแล้ว ✅');
}
*/