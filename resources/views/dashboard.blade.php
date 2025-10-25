<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ระบบการจองและยืมอุปกรณ์ องค์การบริหารนักศึกษา</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    body{font-family:'Prompt',sans-serif;background:linear-gradient(180deg,#ffeaf1,#ffffff)}
    .btn-primary{background:#FF69B4;color:#fff;transition:.2s}
    .btn-primary:hover{background:#ff3c9d}
  </style>
</head>
<body class="min-h-screen flex flex-col justify-between">
  <header class="bg-pink-200 shadow">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <img src="{{ asset('images/abc.jpg') }}" 
     alt="โลโก้ อบศ." 
     class="w-[90px] h-[90px] rounded-full shadow bg-pink-200 object-cover">
      <h1 class="text-2xl font-semibold text-pink-700">ระบบการจองและยืมอุปกรณ์</h1>
      <nav class="space-x-3">
        <a href="/login" class="text-pink-700 hover:text-pink-900 font-medium">เข้าสู่ระบบ</a>
        <a href="/register" class="bg-pink-500 text-white px-4 py-2 rounded-full hover:bg-pink-600">สมัครสมาชิก</a>
      </nav>
    </div>
  </header>

  <section class="max-w-7xl mx-auto px-6 py-16 flex flex-col lg:flex-row items-center justify-between">
    <div class="max-w-xl text-center lg:text-left">
      <h2 class="text-4xl font-bold text-pink-700 mb-4">ยินดีต้อนรับ</h2>
      <p class="text-gray-700 text-lg mb-6">
        ระบบบริหารจัดการอุปกรณ์ขององค์การบริหารนักศึกษา — จอง ยืม คืน ได้ง่าย โปร่งใส และเป็นระบบ
      </p>
      <a href="/booking" class="btn-primary px-6 py-3 rounded-full shadow">เริ่มต้นใช้งาน</a>
    </div>
    <img src="{{ asset('images/booking.png') }}" 
     alt="โลโก้ อบศ." 
     class="w-80 mt-10 lg:mt-0 drop-shadow">
  </section>

  {{-- 🌸 Section ใหม่: ขั้นตอนการทำงานของระบบ --}}
  <section class="relative py-20 bg-gradient-to-b from-white to-pink-50 overflow-hidden">
    {{-- วงกลมเบลอด้านหลัง --}}
    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-[600px] h-[600px] bg-pink-200/20 rounded-full blur-3xl"></div>

    <div class="relative max-w-6xl mx-auto px-6 text-center">
      <h2 class="text-3xl font-bold text-[#FF69B4] mb-4">🚀 ระบบการจองและยืมอุปกรณ์คืออะไร?</h2>
      <p class="text-gray-600 max-w-2xl mx-auto mb-12">
        ระบบขององค์การบริหารนักศึกษาถูกออกแบบเพื่อช่วยให้นักศึกษาสามารถจัดการอุปกรณ์ได้ง่าย  
        ตั้งแต่ขั้นตอน “จอง” “ยืม” และ “คืน” พร้อมระบบติดตามแบบเรียลไทม์
      </p>

      {{-- 🔹 3 ขั้นตอนหลัก --}}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        {{-- ขั้นตอน 1 --}}
        <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition relative overflow-hidden">
          <div class="absolute -top-10 -right-10 w-32 h-32 bg-pink-100 rounded-full blur-xl group-hover:bg-pink-200 transition"></div>
          <div class="relative z-10">
            <div class="bg-pink-500 text-white w-20 h-20 rounded-full flex items-center justify-center text-4xl mx-auto mb-4">📝</div>
            <h3 class="text-xl font-bold text-pink-700 mb-2">ขั้นตอนที่ 1: จองอุปกรณ์</h3>
            <p class="text-gray-600">
              เลือกอุปกรณ์ที่ต้องการผ่านหน้าเว็บได้ทันที  
              ระบบจะบันทึกข้อมูลและแจ้งเตือนเมื่ออนุมัติการจอง
            </p>
          </div>
        </div>

        {{-- ขั้นตอน 2 --}}
        <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition relative overflow-hidden">
          <div class="absolute -top-10 -right-10 w-32 h-32 bg-pink-100 rounded-full blur-xl group-hover:bg-pink-200 transition"></div>
          <div class="relative z-10">
            <div class="bg-pink-500 text-white w-20 h-20 rounded-full flex items-center justify-center text-4xl mx-auto mb-4">🎒</div>
            <h3 class="text-xl font-bold text-pink-700 mb-2">ขั้นตอนที่ 2: ยืมอุปกรณ์</h3>
            <p class="text-gray-600">
              หลังจากจองสำเร็จ สามารถมายืมอุปกรณ์ตามวันเวลา  
              พร้อมบันทึกผู้ยืมอัตโนมัติในระบบ
            </p>
          </div>
        </div>

        {{-- ขั้นตอน 3 --}}
        <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition relative overflow-hidden">
          <div class="absolute -top-10 -right-10 w-32 h-32 bg-pink-100 rounded-full blur-xl group-hover:bg-pink-200 transition"></div>
          <div class="relative z-10">
            <div class="bg-pink-500 text-white w-20 h-20 rounded-full flex items-center justify-center text-4xl mx-auto mb-4">🔁</div>
            <h3 class="text-xl font-bold text-pink-700 mb-2">ขั้นตอนที่ 3: คืนอุปกรณ์</h3>
            <p class="text-gray-600">
              เมื่อคืนอุปกรณ์ ระบบจะอัปเดตสถานะอัตโนมัติ  
              พร้อมเก็บประวัติการยืม–คืนไว้ตรวจสอบภายหลัง
            </p>
          </div>
        </div>
      </div>

    </section>

   {{-- 🔹 Footer --}}
  <footer class="bg-pink-200 py-5 text-center text-pink-700">
    © 2025 ระบบการจองและยืมอุปกรณ์ อบศ. พัฒนาโดยองค์การบริหารนักศึกษา
  </footer>
</body>
</html>
