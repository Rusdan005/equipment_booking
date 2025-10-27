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
        /* 🌟 1. กำหนดสี (เพิ่มสีใหม่ๆ) */
        :root {
            --primary-pink: #FF69B4; /* Deep Pink */
            --primary-pink-light: #ff85c0; /* Light Pink for Gradient */
            --light-pink: #FFEAF1;   /* Light Pink */
            --dark-pink: #cc5490;   /* Darker Pink for hover */
            --secondary-purple: #E6A8FF; /* สีม่วงอ่อนสำหรับ Blob */
            --text-color: #333;
            --footer-bg: var(--primary-pink); /* 💖 [ปรับปรุง] Footer ใช้สีหลัก */
            --footer-text: #FFFFFF;
        }

        /* 🌟 2. โครงสร้าง Body (ใช้ของเดิมที่น้องส่งมาเป๊ะๆ) */
        body {
            font-family: 'Prompt', sans-serif;
            /* 💖 [ปรับปรุง] เปลี่ยนเป็น Gradient อ่อนๆ */
            background: linear-gradient(180deg, var(--light-pink) 0%, #ffffff 50%);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* 🌟 3. Custom Scrollbar (ลูกเล่นใหม่) */
        ::-webkit-scrollbar {
            width: 10px;
            background-color: var(--light-pink);
        }
        ::-webkit-scrollbar-thumb {
            background-color: var(--primary-pink);
            border-radius: 10px;
            border: 2px solid var(--light-pink);
        }
        ::-webkit-scrollbar-thumb:hover {
            background-color: var(--dark-pink);
        }

        .container-wrapper {
            max-width: 1200px; 
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        /* 🌟 4. Header (เพิ่ม Frosted Glass) */
        header {
            transition: all 0.3s ease-in-out;
        }
        header.scrolled {
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        
        /* 🌟 5. ปุ่ม (อัปเกรดเป็น Gradient) */
        .btn-primary {
            /* 💖 [ปรับปรุง] เปลี่ยนเป็น Gradient */
            background: linear-gradient(45deg, var(--primary-pink), var(--primary-pink-light));
            background-size: 150% 150%; 
            background-position: 50% 50%;
            color: #fff;
            font-weight: 600;
            transition: all 0.4s ease-in-out;
            box-shadow: 0 4px 15px rgba(255, 105, 180, 0.4);
            transform-origin: center;
        }
        .btn-primary:hover {
            /* 💖 [ปรับปรุง] ขยับ Gradient */
            background-position: 100% 50%;
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 105, 180, 0.6);
        }
        .btn-primary:active {
            transform: scale(0.98) translateY(0);
            box-shadow: 0 2px 10px rgba(255, 105, 180, 0.4);
        }

        /* 🌟 6. การ์ด (อัปเกรด Shine + Icon) */
        .bg-card-pink {
            background-color: #fff0f5; 
            transition: all 0.3s ease-in-out;
            transform: translateY(0);
            position: relative;
            overflow: hidden;
        }
        .bg-card-pink:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }
        .card-icon {
            transition: transform 0.4s ease-out;
        }
        .bg-card-pink:hover .card-icon {
            transform: scale(1.2) rotate(15deg);
        }
        .bg-card-pink::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 75%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
            transform: skewX(-25deg);
            transition: left 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        }
        .bg-card-pink:hover::before {
            left: 150%;
        }
        
        /* 🌟 7. Keyframes (สำหรับ Animation ต่างๆ) */
        @keyframes bob {
            0%, 100% { transform: translateY(0) rotate(3deg); }
            50% { transform: translateY(-10px) rotate(3deg); }
        }
        @keyframes moveBlob1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(50px, -20px) scale(1.1); }
        }
        @keyframes moveBlob2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-30px, 40px) scale(0.9); }
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }


        /* 🌟 8. Scroll-Reveal (สำหรับซ่อน/แสดง) */
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
        }
        .reveal-on-scroll.revealed {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* 🌟 9. Animated Gradient Text */
        .text-gradient-animated {
            background: linear-gradient(45deg, var(--primary-pink), #ff8c00, var(--primary-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
            background-size: 200% 200%; 
            animation: gradientShift 4s ease-in-out infinite;
        }

        /* 🌟 10. CSS เดิม (เผื่อไว้) */
        .header-link {
            color: var(--text-color);
            transition: color 0.2s ease-in-out;
        }
        .header-link:hover {
            color: var(--primary-pink);
        }
        .bg-footer-pink {
            background-color: var(--footer-bg);
        }
        .text-primary-pink {
            color: var(--primary-pink);
        }
    </style>
</head>

<body>

    {{-- 🌟 Header/Navbar (ปรับขนาดใหญ่ขึ้น) --}}
    <header class="bg-white shadow-sm sticky top-0 z-50">
        {{-- 💖 [ปรับปรุง] เพิ่ม py-6 --}}
        <div class="container-wrapper py-6 flex items-center justify-between">
            
            {{-- Logo/Site Title --}}
            <div class="flex items-center space-x-5">
                
                {{-- 💖 [เพิ่มลูกเล่น] โลโก้ขยับได้ (w-16) --}}
                <img src="{{ asset('images/abc.jpg') }}" 
                     alt="โลโก้ อบศ." 
                     class="w-16 h-16 rounded-full object-cover transition-all duration-300 hover:rotate-12 hover:scale-110">
                     
                {{-- 💖 [ปรับปรุง] text-4xl และแก้ span ที่พัง --}}
                <a href="/" class="text-4xl font-bold text-gray-800 hover:text-primary-pink transition-colors">
                    ระบบการ<span class="text-primary-pink">จอง</span>
                </a>
            </div>

            {{-- Navigation Links --}}
            <nav>
                {{-- 💖 [ปรับปรุง] ขยายปุ่ม (text-lg, px-6 py-3) --}}
                <div class="hidden sm:flex space-x-4 items-center">
                    <a href="/login" 
                       class="text-primary-pink font-semibold px-6 py-3 rounded-full text-lg transition-colors duration-300 hover:bg-pink-100">
                       เข้าสู่ระบบ
                    </a>
                    <a href="/register" 
                       class="btn-primary px-6 py-3 rounded-full text-lg">
                       สมัครสมาชิก
                    </a>
                </div>
            </nav>
        </div>
    </header>

    {{-- 💖💖💖 นี่คือส่วนที่สำคัญที่สุด 💖💖💖 --}}
    {{-- 💖 น้องใช้ flex-grow ซึ่งถูกต้องแล้ว! --}}
    <main class="flex-grow">

        {{-- 💖 Hero Section (เพิ่มลูกเล่น) --}}
        <section class="py-20 lg:py-24 relative overflow-hidden">
            
            {{-- 🌟 [ใหม่] Animated Blobs --}}
            <div class="absolute top-0 left-0 w-full h-full z-0 opacity-30">
                <div class="absolute top-1/4 left-1/4 w-72 h-72 bg-primary-pink rounded-full"
                     style="filter: blur(80px); animation: moveBlob1 10s ease-in-out infinite;">
                </div>
                <div class="absolute bottom-1/4 right-1/4 w-72 h-72 bg-secondary-purple rounded-full"
                     style="filter: blur(80px); animation: moveBlob2 12s ease-in-out infinite reverse;">
                </div>
            </div>

            <div class="container-wrapper flex flex-col lg:flex-row items-center justify-between gap-10">
                
                {{-- Text Content (z-10) --}}
                <div class="max-w-2xl text-center lg:text-left z-10">
                    
                    {{-- 💖 [ปรับปรุง] Animated Gradient Text + Scroll Reveal --}}
                    <h2 class="text-5xl lg:text-6xl font-extrabold leading-tight mb-4 drop-shadow-md text-gradient-animated reveal-on-scroll">
                        ยินดีต้อนรับ
                    </h2>
                    <p class="text-lg lg:text-xl text-gray-700 mb-8 leading-relaxed drop-shadow-sm reveal-on-scroll" 
                       style="transition-delay: 0.2s;">
                        ระบบจัดการอุปกรณ์ขององค์การบริหารนักศึกษา ที่ช่วยให้คุณจอง ยืม และคืนอุปกรณ์ได้อย่างง่ายดาย โปร่งใส และเป็นระบบ เพื่อสนับสนุนทุกกิจกรรมของนักศึกษา
                    </p>
                    {{-- 💖 [ปรับปรุง] ปุ่ม Gradient + Scroll Reveal --}}
                    <a href="/booking" class="btn-primary px-8 py-3 rounded-full shadow-xl text-lg reveal-on-scroll" 
                       style="transition-delay: 0.4s;">
                        เริ่มต้นใช้งานระบบ
                    </a>
                </div>

                {{-- Image Illustration (z-10) --}}
                <div class="w-full max-w-sm lg:max-w-md z-10 -mr-16 lg:mr-0">
                    {{-- 💖 [ปรับปรุง] ใช้ anim-bob (ลบ hover effect เดิมออก) --}}
                    <img src="{{ asset('images/booking.png') }}" 
                         alt="ภาพประกอบระบบ จองและยืมอุปกรณ์" 
                         class="w-full h-auto drop-shadow-2xl anim-bob">
                </div>
            </div>
        </section>

        {{-- 💡 Section: ภาพรวมระบบ / คุณสมบัติ --}}
        <section class="py-16 bg-transparent"> 
            <div class="container-wrapper text-center">
                {{-- 💖 [ปรับปรุง] เพิ่ม Scroll Reveal --}}
                <h3 class="text-3xl font-bold text-gray-800 mb-4 reveal-on-scroll">
                    ระบบการจองและยืมอุปกรณ์ของเรา
                </h3>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto mb-12 reveal-on-scroll" 
                   style="transition-delay: 0.2s;">
                    เรามุ่งมั่นที่จะมอบประสบการณ์ที่ดีที่สุดในการจัดการอุปกรณ์สำหรับนักศึกษา ด้วยคุณสมบัติเด่นที่ออกแบบมาเพื่อคุณ
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-10">
                    
                    {{-- 💖 [ปรับปรุง] เพิ่ม Scroll Reveal และ class 'card-icon' --}}
                    {{-- Card 1: ใช้งานง่าย --}}
                    <div class="bg-card-pink p-8 rounded-xl shadow-lg border-t-4 border-primary-pink reveal-on-scroll" 
                         style="transition-delay: 0.4s;">
                        <div class="card-icon text-5xl mb-4 text-primary-pink">✨</div>
                        <h4 class="text-xl font-semibold text-gray-800 mb-2">ออกแบบมาให้ใช้งานง่าย</h4>
                        <p class="text-gray-600">
                            หน้าตาเป็นมิตร ใช้งานได้ทั้งบนคอมพิวเตอร์และมือถือ ไม่ซับซ้อน เข้าใจได้ในไม่กี่คลิก
                        </p>
                    </div>

                    {{-- Card 2: ตรวจสอบสถานะได้จริง --}}
                    <div class="bg-card-pink p-8 rounded-xl shadow-lg border-t-4 border-primary-pink reveal-on-scroll" 
                         style="transition-delay: 0.6s;">
                        <div class="card-icon text-5xl mb-4 text-primary-pink">📊</div>
                        <h4 class="text-xl font-semibold text-gray-800 mb-2">ตรวจสอบสถานะแบบเรียลไทม์</h4>
                        <p class="text-gray-600">
                            ดูสถานะการจอง การยืม และอุปกรณ์ที่ว่างได้ทันที ทำให้วางแผนการใช้งานได้ง่าย
                        </p>
                    </div>

                    {{-- Card 3: จัดการข้อมูลส่วนตัว --}}
                    <div class="bg-card-pink p-8 rounded-xl shadow-lg border-t-4 border-primary-pink reveal-on-scroll" 
                         style="transition-delay: 0.8s;">
                        <div class="card-icon text-5xl mb-4 text-primary-pink">👤</div> 
                        <h4 class="text-xl font-semibold text-gray-800 mb-2">จัดการข้อมูลส่วนตัว</h4>
                        <p class="text-gray-600">
                            ตรวจสอบประวัติการยืม-คืนอุปกรณ์ และอัปเดตข้อมูลส่วนตัวของคุณได้อย่างรวดเร็ว
                        </p>
                    </div>
                </div>
            </div>
        </section>       
    </main> {{-- 💖 สิ้นสุด Main --}}


    {{-- 🔒 Footer --}}
    {{-- 💖 [ไม่มีอะไรเปลี่ยนแปลง] Footer จะแสดงผลตามปกติ --}}
    <footer class="bg-footer-pink text-white py-6 text-center mt-auto">
        <div class="container-wrapper">
            <p class="text-base font-medium mb-3">
                © 2025 ระบบการจองและยืมอุปกรณ์ องค์การบริหารนักศึกษา
            </p>
        </div>
    </footer>


    {{-- 🌟 JavaScript สำหรับลูกเล่น (Frosted Header, Scroll Reveal) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- 1. Frosted Glass Header ---
            const header = document.querySelector('header');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) { 
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });

            // --- 2. Scroll-Triggered Animations ---
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        observer.unobserve(entry.target); // เล่นแค่ครั้งเดียว
                    }
                });
            }, {
                threshold: 0.1 // เริ่มทำงานเมื่อเห็น 10%
            });
            
            const elementsToReveal = document.querySelectorAll('.reveal-on-scroll');
            elementsToReveal.forEach(el => observer.observe(el));

            // --- 3. Interactive Mouse Effects (Parallax Blobs & Image Tilt) ---
            // 💖 [เพิ่มกลับเข้ามา] Logic เดิมของ Parallax Blobs และ Image Tilt
            const blob1 = document.querySelector('.absolute.top-1\\/4.left-1\\/4'); // เลือก blob1 จาก class
            const blob2 = document.querySelector('.absolute.bottom-1\\/4.right-1\\/4'); // เลือก blob2 จาก class
            const heroImageContainer = document.querySelector('.w-full.max-w-sm.lg\\:max-w-md.-mr-16.lg\\:mr-0'); // เลือก hero image container

            window.addEventListener('mousemove', function(e) {
                const x = (e.clientX / window.innerWidth) * 2 - 1;
                const y = (e.clientY / window.innerHeight) * 2 - 1;

                // 3.1: Parallax Blobs
                const blobMoveFactor = 30;
                if (blob1 && blob2) {
                    blob1.style.transform = `translate(${x * blobMoveFactor}px, ${y * blobMoveFactor}px)`;
                    blob2.style.transform = `translate(${-x * blobMoveFactor}px, ${-y * blobMoveFactor}px)`;
                }
                
                // 3.2: Image Tilt
                const tiltFactor = 10;
                if (heroImageContainer) {
                    heroImageContainer.style.transform = `perspective(1000px) rotateY(${x * tiltFactor}deg) rotateX(${-y * tiltFactor}deg)`;
                }
            });

            // 3.3: Reset Image Tilt
            if (heroImageContainer) {
                heroImageContainer.addEventListener('mouseleave', function() {
                    heroImageContainer.style.transform = `perspective(1000px) rotateY(0deg) rotateX(0deg)`;
                });
            }
        });
    </script>

</body>
</html>