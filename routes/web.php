<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ManageBookingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| เส้นทางหลักของระบบจองและยืมอุปกรณ์
|--------------------------------------------------------------------------
*/

// ✅ หน้าแสดงรายการอุปกรณ์
Route::get('/equipment', [BookingController::class, 'equipmentList'])->name('equipment.index');

// ✅ หน้าฟอร์มจอง
Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');

// ✅ เก็บข้อมูลฟอร์มจอง
Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');

// ✅ หน้ารายการจอง
Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');

// 🏠 หน้าแรก (Welcome)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 📅 ระบบจองอุปกรณ์ (เฉพาะผู้ที่ล็อกอินแล้ว)
Route::middleware(['auth'])->group(function () {
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
});

// 🧑‍💼 แดชบอร์ดแอดมิน
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

// 👤 โปรไฟล์ผู้ใช้
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/booking/create', [App\Http\Controllers\BookingController::class, 'create'])->name('booking.create');

// 📊 หน้า Dashboard หลังล็อกอิน
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| 🔹 เส้นทางสำหรับผู้ดูแล (Admin/Staff)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::prefix('manage/bookings')->name('manage.bookings.')->group(function () {

        // 📝 พิจารณาการจอง
        Route::get('review', [ManageBookingController::class, 'reviewIndex'])
            ->name('review.index');

        Route::get('review/{booking}', [ManageBookingController::class, 'reviewShow'])
            ->name('review.show');

        Route::post('review/{booking}/approve', [ManageBookingController::class, 'approve'])
            ->name('review.approve');

        Route::post('review/{booking}/reject', [ManageBookingController::class, 'reject'])
            ->name('review.reject');

        // 📦 มารับอุปกรณ์
        Route::get('pickup', [ManageBookingController::class, 'pickupIndex'])
            ->name('pickup.index');

        Route::post('pickup/{booking}', [ManageBookingController::class, 'pickup'])
            ->name('pickup.do');
    });
});

// 🔐 ระบบ Auth (Login / Register / Forgot Password)
require __DIR__ . '/auth.php';
