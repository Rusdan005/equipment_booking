<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * กำหนด policy (ตอนนี้ยังไม่ใช้)
     */
    protected $policies = [
        // Model::class => Policy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        /**
         * ✅ Gate: เฉพาะ admin / staff เท่านั้นที่เข้าได้
         * ปรับชื่อ role ได้ตามระบบของซัน เช่น user_type, position เป็นต้น
         */
        Gate::define('manage-bookings', function ($user) {
            return in_array($user->role ?? 'user', ['admin', 'staff']);
        });
    }
}
