<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // ตรวจสอบว่า role เป็น admin หรือไม่
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return back()->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบผู้ดูแล');
            }
        }

        return back()->with('error', 'อีเมลหรือรหัสผ่านไม่ถูกต้อง');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('error', 'ออกจากระบบแล้ว');
    }
}
