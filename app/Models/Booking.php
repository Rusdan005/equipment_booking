<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Booking extends Model
{
    use HasFactory;

    /**
     * 🧩 กำหนดคอลัมน์ที่สามารถบันทึกได้ (fillable)
     */
    protected $fillable = [
        'user_id',
        'equipment_id',
        'start_at',
        'end_at',
        'status',
        'approved_by',
        'approved_at',
        'reject_reason',
        'picked_up_at',
        'picked_up_by',
        'pickup_code',
    ];

    /**
     * 🕒 ระบุว่า column ไหนคือวันที่/เวลา
     */
    protected $dates = [
        'start_at',
        'end_at',
        'approved_at',
        'picked_up_at',
    ];

    /**
     * 🤝 ความสัมพันธ์กับตารางอื่น
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function picker()
    {
        return $this->belongsTo(User::class, 'picked_up_by');
    }

    /**
     * 🔍 Scopes: ฟังก์ชันช่วยกรองข้อมูล
     */
    public function scopePending(Builder $query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved(Builder $query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeReadyForPickup(Builder $query)
    {
        return $query->where('status', 'approved')
                     ->whereNull('picked_up_at');
    }

    /**
     * 🎨 ฟังก์ชันช่วยตกแต่ง badge สีสถานะ
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'picked_up'=> 'bg-blue-100 text-blue-800',
            default    => 'bg-gray-100 text-gray-800',
        };
    }
}
