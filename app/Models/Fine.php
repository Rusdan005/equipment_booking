<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'amount',
        'reason',
        'damage_level',
        'days_late',
        'status',
        'due_date',
        'paid_at',
    ];

    // 🔗 ความสัมพันธ์กับการจอง
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // 🔗 ความสัมพันธ์กับผู้ใช้ (ผู้ถูกปรับ)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ ตัวช่วยสำหรับแสดงสถานะค่าปรับ
    public function getStatusLabelAttribute()
    {
        return $this->status === 'paid' ? 'ชำระแล้ว' : 'ยังไม่ชำระ';
    }
}
