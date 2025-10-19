<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * ตรวจสอบสิทธิ์ของผู้ใช้ก่อนเข้าหน้าเฉพาะ role
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // ถ้ายังไม่ล็อกอิน
        if (!Auth::check()) {
            return redirect('/login');
        }

        // ถ้า role ไม่ตรง เช่นไม่ใช่ admin
        if (Auth::user()->role !== $role) {
            return redirect('/dashboard')->with('error', 'คุณไม่มีสิทธิ์เข้าหน้านี้');
        }

        return $next($request);
    }
}
