<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * 🧩 คอลัมน์ที่อนุญาตให้บันทึกข้อมูลได้
     */
    protected $fillable = [
        'user_id',
        'equipment_id',
        'borrow_date',
        'return_date',
        'purpose',
        'location',
        'status',
        'approved_by',
        'approved_at',
        'reject_reason',
        'picked_up_at',
        'picked_up_by',
        'pickup_code',
        'returned_at',
    ];

    /**
     * 🕒 ระบุฟิลด์ที่เป็นวันที่ (date type)
     */
    protected $dates = [
        'borrow_date',
        'return_date',
        'approved_at',
        'picked_up_at',
        'returned_at',
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
     * 🎨 ฟังก์ชันช่วยตกแต่ง badge สีสถานะ
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'picked_up'=> 'bg-blue-100 text-blue-800',
            'returned' => 'bg-green-200 text-green-900',
            'overdue'  => 'bg-red-200 text-red-900',
            default    => 'bg-gray-100 text-gray-800',
        };
    }
}
