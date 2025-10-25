<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * ตรวจสอบสิทธิ์ role ของผู้ใช้
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // ✅ ตรวจสอบการล็อกอิน และ role ที่ได้รับอนุญาต
        if (!$user || !in_array($user->role, $roles)) {
            // ❌ ถ้าไม่ผ่าน ให้ redirect กลับ Dashboard พร้อมแจ้งเตือน
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าหน้านี้');
        }

        return $next($request);
    }
}
