<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class MasterDataController extends Controller
{
    public function __construct()
    {
        // ต้องล็อกอิน และต้องเป็น admin หรือ staff (middleware role ถูก alias แล้วใน bootstrap/app.php)
        $this->middleware(['auth', 'role:admin,staff']);
    }

    public function index()
    {
        // view: resources/views/manage/masterdata-index.blade.php
        return view('manage.masterdata-index');
    }
}
