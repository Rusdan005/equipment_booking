<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * ตัวอย่างการเรียกใช้:
     * php artisan make:admin
     */
    protected $signature = 'make:admin';

    /**
     * The console command description.
     */
    protected $description = 'สร้างบัญชีผู้ดูแลระบบ (Admin Account)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== สร้างบัญชีผู้ดูแลระบบใหม่ ===');

        $name = $this->ask('ชื่อผู้ใช้ (เช่น ซัน)');
        $email = $this->ask('อีเมล (เช่น admin@yru.ac.th)');
        $password = $this->secret('รหัสผ่าน (จะไม่แสดงขณะพิมพ์)');

        // ตรวจสอบว่ามีอีเมลนี้อยู่แล้วหรือไม่
        if (User::where('email', $email)->exists()) {
            $this->error('❌ มีอีเมลนี้ในระบบแล้ว!');
            return;
        }

        // สร้างผู้ใช้ใหม่
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        $this->info('✅ สร้างบัญชีแอดมินสำเร็จ!');
        $this->info("ชื่อผู้ใช้: {$name}");
        $this->info("อีเมล: {$email}");
    }
}
