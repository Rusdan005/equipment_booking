<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * ตรวจสอบว่า user มี role ที่ระบุหรือไม่
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // ถ้ายังไม่ได้ล็อกอิน หรือ role ไม่ตรง
        if (!$user || !in_array($user->role, $roles)) {
            // ❌ ถ้าไม่ผ่าน ให้เด้งกลับหน้าแรกแทน (ไม่ error)
            return redirect()->route('dashboard')->with('error', 'คุณไม่มีสิทธิ์เข้าหน้านี้');
        }

        return $next($request);
    }
}
