<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ระบบการจองและยืมอุปกรณ์ องค์การบริหารนักศึกษา</title>

    {{-- Tailwind + Font --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        /* กำหนดสีหลักของธีม */
        :root {
            --primary-pink: #FF69B4; /* Deep Pink - สีหลัก, และเป็นสี Footer ใหม่ */
            --light-pink: #FFEAF1; /* Light Pink - สีพื้นหลังหลักของเว็บไซต์ */
            --dark-pink: #cc5490; /* Darker Pink for hover */
            --text-color: #333; /* สีข้อความปกติ */
            --secondary-text-color: #666;
            
            /* สีสำหรับ Footer ใหม่ */
            --footer-bg: var(--primary-pink); 
            --footer-text: #FFFFFF;
        }

        body {
            font-family: 'Prompt', sans-serif;
            /* พื้นหลังหลักเป็นสีชมพูอ่อนทั้งหน้า */
            background-color: var(--light-pink); 
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .container-wrapper {
            max-width: 1200px; 
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .btn-primary {
            background: var(--primary-pink);
            color: #fff;
            font-weight: 600;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 15px rgba(255, 105, 180, 0.4);
        }
        .btn-primary:hover {
            background: var(--dark-pink);
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(255, 105, 180, 0.6);
        }
        .header-link {
            color: var(--text-color);
            transition: color 0.2s ease-in-out;
        }
        .header-link:hover {
            color: var(--primary-pink);
        }

        /* Utility class สำหรับสีชมพูเข้มของ Footer */
        .bg-footer-pink {
            background-color: var(--footer-bg);
        }
        
        /* Utility class สำหรับ Card Background สีชมพูอ่อนมากๆ */
        .bg-card-pink {
            background-color: #fff0f5; 
        }
        
        /* Text Color for primary pink */
        .text-primary-pink {
            color: var(--primary-pink);
        }
    </style>
</head>

<body>

    {{-- 🌟 Header/Navbar --}}
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container-wrapper py-4 flex items-center justify-between">
            
            {{-- Logo/Site Title --}}
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/abc.jpg') }}" 
                    alt="โลโก้ อบศ." 
                    class="w-10 h-10 rounded-full object-cover">
                <a href="/" class="text-2xl font-bold text-gray-800 hover:text-primary-pink transition-colors">
                    <span class="text-primary-pink"></span> ระบบการจอง
                </a>
            </div>

            {{-- Navigation Links --}}
            <nav>
                {{-- ปุ่มเข้าสู่ระบบและสมัครสมาชิก --}}
                <div class="hidden sm:flex space-x-3">
                    <a href="/login" class="text-primary-pink font-semibold hover:underline">เข้าสู่ระบบ</a>
                    <a href="/register" class="btn-primary px-4 py-2 rounded-full text-sm">สมัครสมาชิก</a>
                </div>
            </nav>
        </div>
    </header>

    <main class="flex-grow">
        {{-- 💖 Hero Section --}}
        <section class="py-20 lg:py-24 relative overflow-hidden">
            <div class="container-wrapper flex flex-col lg:flex-row items-center justify-between gap-10">
                
                {{-- Text Content --}}
                <div class="max-w-2xl text-center lg:text-left z-10">
                    <h2 class="text-5xl lg:text-6xl font-extrabold leading-tight mb-4 drop-shadow-md text-primary-pink">
                        ยินดีต้อนรับ
                    </h2>
                    <p class="text-lg lg:text-xl text-gray-700 mb-8 leading-relaxed drop-shadow-sm">
                        ระบบจัดการอุปกรณ์ขององค์การบริหารนักศึกษา ที่ช่วยให้คุณจอง ยืม และคืนอุปกรณ์ได้อย่างง่ายดาย โปร่งใส และเป็นระบบ เพื่อสนับสนุนทุกกิจกรรมของนักศึกษา
                    </p>
                    <a href="/booking" class="btn-primary px-8 py-3 rounded-full shadow-xl text-lg">
                        เริ่มต้นใช้งานระบบ
                    </a>
                </div>

                {{-- Image Illustration --}}
                <div class="w-full max-w-sm lg:max-w-md z-10 -mr-16 lg:mr-0">
                    <img src="{{ asset('images/booking.png') }}" 
                        alt="ภาพประกอบระบบ จองและยืมอุปกรณ์" 
                        class="w-full h-auto drop-shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-500 ease-in-out">
                </div>
            </div>
        </section>

        {{-- 💡 Section: ภาพรวมระบบ / คุณสมบัติ --}}
        <section class="py-16 bg-transparent"> 
            <div class="container-wrapper text-center">
                <h3 class="text-3xl font-bold text-gray-800 mb-4">
                    ระบบการจองและยืมอุปกรณ์คืออะไร?
                </h3>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto mb-12">
                    เรามุ่งมั่นที่จะมอบประสบการณ์ที่ดีที่สุดในการจัดการอุปกรณ์สำหรับนักศึกษา ด้วยคุณสมบัติเด่นที่ออกแบบมาเพื่อคุณ
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-10">
                    {{-- Card 1: ใช้งานง่าย --}}
                    <div class="bg-card-pink p-8 rounded-xl shadow-lg border-t-4 border-primary-pink hover:shadow-xl transition-all duration-300">
                        <div class="text-5xl mb-4 text-primary-pink">✨</div>
                        <h4 class="text-xl font-semibold text-gray-800 mb-2">ออกแบบมาให้ใช้งานง่าย</h4>
                        <p class="text-gray-600">
                            หน้าตาเป็นมิตร ใช้งานได้ทั้งบนคอมพิวเตอร์และมือถือ ไม่ซับซ้อน เข้าใจได้ในไม่กี่คลิก
                        </p>
                    </div>

                    {{-- Card 2: ตรวจสอบสถานะได้จริง --}}
                    <div class="bg-card-pink p-8 rounded-xl shadow-lg border-t-4 border-primary-pink hover:shadow-xl transition-all duration-300">
                        <div class="text-5xl mb-4 text-primary-pink">📊</div>
                        <h4 class="text-xl font-semibold text-gray-800 mb-2">ตรวจสอบสถานะแบบเรียลไทม์</h4>
                        <p class="text-gray-600">
                            ดูสถานะการจอง การยืม และอุปกรณ์ที่ว่างได้ทันที ทำให้วางแผนการใช้งานได้ง่าย
                        </p>
                    </div>

                    {{-- 🟢 Card 3: เปลี่ยนเป็น จัดการข้อมูลส่วนตัว --}}
                    <div class="bg-card-pink p-8 rounded-xl shadow-lg border-t-4 border-primary-pink hover:shadow-xl transition-all duration-300">
                        {{-- เปลี่ยนไอคอน --}}
                        <div class="text-5xl mb-4 text-primary-pink">👤</div> 
                        {{-- เปลี่ยนหัวข้อ --}}
                        <h4 class="text-xl font-semibold text-gray-800 mb-2">จัดการข้อมูลส่วนตัว</h4>
                        {{-- เปลี่ยนข้อความ --}}
                        <p class="text-gray-600">
                            ตรวจสอบประวัติการยืม-คืนอุปกรณ์ และอัปเดตข้อมูลส่วนตัวของคุณได้อย่างรวดเร็ว
                        </p>
                    </div>
                </div>
            </div>
        </section>       

    {{-- 🔒 Footer --}}
    <footer class="bg-footer-pink text-white py-6 text-center mt-auto">
        <div class="container-wrapper">
            <p class="text-base font-medium mb-3">
                © 2025 ระบบการจองและยืมอุปกรณ์ องค์การบริหารนักศึกษา
            </p>
            
    </footer>

</body>
</html>