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

  <section class="bg-white py-14 shadow-inner">
    <h3 class="text-center text-3xl font-semibold text-pink-700 mb-10">ทีมผู้พัฒนา</h3>
    <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="bg-pink-100 rounded-2xl p-6 text-center hover:shadow-lg">
        <img class="w-20 mx-auto mb-3" src="https://cdn-icons-png.flaticon.com/512/2922/2922506.png">
        <h4 class="text-xl font-bold text-pink-700">ซัน</h4>
        <p class="text-gray-600">หัวหน้าโปรเจกต์ / Backend Lead</p>
      </div>
      <div class="bg-pink-100 rounded-2xl p-6 text-center hover:shadow-lg">
        <img class="w-20 mx-auto mb-3" src="https://cdn-icons-png.flaticon.com/512/2922/2922565.png">
        <h4 class="text-xl font-bold text-pink-700">รุสดี</h4>
        <p class="text-gray-600">Frontend / UI Designer</p>
      </div>
      <div class="bg-pink-100 rounded-2xl p-6 text-center hover:shadow-lg">
        <img class="w-20 mx-auto mb-3" src="https://cdn-icons-png.flaticon.com/512/2922/2922561.png">
        <h4 class="text-xl font-bold text-pink-700">อามีน</h4>
        <p class="text-gray-600">Booking & Borrow Dev</p>
      </div>
      <div class="bg-pink-100 rounded-2xl p-6 text-center hover:shadow-lg">
        <img class="w-20 mx-auto mb-3" src="https://cdn-icons-png.flaticon.com/512/2922/2922510.png">
        <h4 class="text-xl font-bold text-pink-700">การิม</h4>
        <p class="text-gray-600">Reports & Integration</p>
      </div>
    </div>
  </section>

  <footer class="bg-pink-200 py-5 text-center text-pink-700">
    © 2025 ระบบการจองและยืมอุปกรณ์ อบอน. พัฒนาโดยทีม ซัน • รุสดี • อามีน • การิม
  </footer>
</body>
</html>
