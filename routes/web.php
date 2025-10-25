<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ManageBookingController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\ManageFineController;

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
| 🎒 Booking Routes (ต้องล็อกอิน)
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

    // ✅ ตรวจสอบการคืนอุปกรณ์ (ฝั่งผู้ใช้)
    Route::get('/booking/return', [BookingController::class, 'returnList'])
        ->name('booking.return.list');

    // ✅ ทำเครื่องหมายว่า "คืนแล้ว" (ผู้ใช้)
    Route::put('/booking/return/{id}', [BookingController::class, 'markAsReturned'])
        ->name('booking.return');

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

    // ⚙️ จัดการข้อมูลพื้นฐาน
    Route::get('/manage/masterdata', [MasterDataController::class, 'index'])
        ->name('manage.masterdata.index');

    // 📝 การจัดการการจอง
    Route::prefix('manage/bookings')->name('manage.bookings.')->group(function () {

        // 🟠 พิจารณาการจอง
        Route::get('review', [ManageBookingController::class, 'reviewIndex'])->name('review.index');
        Route::get('review/{booking}', [ManageBookingController::class, 'reviewShow'])->name('review.show');
        Route::post('review/{booking}/approve', [ManageBookingController::class, 'approve'])->name('review.approve');
        Route::post('review/{booking}/reject', [ManageBookingController::class, 'reject'])->name('review.reject');

        // 🟢 มารับอุปกรณ์
        Route::get('pickup', [ManageBookingController::class, 'pickupIndex'])->name('pickup.index');
        Route::post('pickup/{booking}', [ManageBookingController::class, 'pickup'])->name('pickup.do');

        // 📜 ประวัติการจองทั้งหมด
        Route::get('history', [ManageBookingController::class, 'historyIndex'])->name('history.index');

        // 📊 ตรวจสอบและยืนยันการคืนอุปกรณ์ (Admin)
        Route::get('returns', [ManageBookingController::class, 'returnsIndex'])->name('returns.index');
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

    // 🧑‍💼 แดชบอร์ดแอดมิน
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');
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
