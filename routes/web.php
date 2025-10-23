<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ManageBookingController;
use App\Http\Controllers\Admin\MasterDataController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('welcome'))->name('home');

// 🏠 หน้าแรก
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 📋 หน้ารายการอุปกรณ์ (ทุกคนดูได้)
Route::get('/equipment', [BookingController::class, 'equipmentList'])->name('equipment.index');

// 📅 ระบบจองอุปกรณ์ (เฉพาะผู้ล็อกอิน)
Route::middleware(['auth'])->group(function () {

    // ✅ แสดงรายการอุปกรณ์ที่ว่างให้จอง
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');

    // ✅ ฟอร์มจองอุปกรณ์
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');

    // ✅ บันทึกข้อมูลการจอง
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');

    // ✅ ตรวจสอบการคืนอุปกรณ์
    Route::get('/booking/return', [BookingController::class, 'returnList'])->name('booking.return.list');

    // ✅ ทำเครื่องหมายว่า "คืนแล้ว"
    Route::put('/booking/return/{id}', [BookingController::class, 'markAsReturned'])->name('booking.return');

    // 👤 โปรไฟล์ผู้ใช้
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin / Staff Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,staff'])->group(function () {

    // 🧩 จัดการข้อมูลพื้นฐาน
    Route::get('/manage/masterdata', [MasterDataController::class, 'index'])
        ->name('manage.masterdata.index');

    // 📝 พิจารณาการจอง / 📦 มารับอุปกรณ์ / 📜 ประวัติทั้งหมด
    Route::prefix('manage/bookings')->name('manage.bookings.')->group(function () {
        // พิจารณาการจอง
        Route::get('review', [ManageBookingController::class, 'reviewIndex'])->name('review.index');
        Route::get('review/{booking}', [ManageBookingController::class, 'reviewShow'])->name('review.show');
        Route::post('review/{booking}/approve', [ManageBookingController::class, 'approve'])->name('review.approve');
        Route::post('review/{booking}/reject', [ManageBookingController::class, 'reject'])->name('review.reject');

        // มารับอุปกรณ์
        Route::get('pickup', [ManageBookingController::class, 'pickupIndex'])->name('pickup.index');
        Route::post('pickup/{booking}', [ManageBookingController::class, 'pickup'])->name('pickup.do');

        // 📜 หน้าประวัติการจองทั้งหมด (ใหม่)
        Route::get('history', [ManageBookingController::class, 'historyIndex'])->name('history.index');
    });

    // 🧑‍💼 แดชบอร์ดแอดมิน
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

// 📊 หน้า Dashboard หลังล็อกอิน
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 🔐 ระบบ Auth (Login / Register / Forgot Password)
require __DIR__ . '/auth.php';
