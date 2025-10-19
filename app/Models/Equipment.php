<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    // ✅ บอก Laravel ว่า Model นี้ใช้ตารางชื่อ "equipments"
    protected $table = 'equipments';

    protected $fillable = [
        'name',
        'type',
        'description',
        'available',
        'total',
        'image',
        'is_available',
    ];
}
