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

/*
|--------------------------------------------------------------------------
| Booking Routes (User)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // 🎒 รายการอุปกรณ์ & การจอง
    Route::get('/equipment', [BookingController::class, 'equipmentList'])->name('equipment.index');
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');

    // 📅 หน้ากำหนดรับอุปกรณ์ของฉัน
    Route::get('/my/pickups', [BookingController::class, 'myPickups'])->name('pickups.mine');
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

/*
|--------------------------------------------------------------------------
| Profile & Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__ . '/auth.php';
