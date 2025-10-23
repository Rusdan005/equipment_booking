<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-700 leading-tight">
            ⚙️ จัดการข้อมูลพื้นฐาน
        </h2>
    </x-slot>

    <div class="py-8 bg-gradient-to-b from-pink-50 to-white min-h-[60vh]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- อุปกรณ์ -->
                <a href="{{ route('equipment.index') }}"
                   class="block bg-white border border-pink-100 rounded-2xl p-6 shadow hover:shadow-pink-200 transition hover:-translate-y-0.5">
                    <div class="text-4xl mb-2">🧰</div>
                    <div class="font-bold text-lg text-gray-800">อุปกรณ์</div>
                    <div class="text-sm text-gray-500 mt-1">ดู/จัดการรายการอุปกรณ์</div>
                </a>

                <!-- หมวดหมู่อุปกรณ์ (จะทำภายหลัง) -->
                <a href="#"
                   class="block bg-white border border-pink-100 rounded-2xl p-6 shadow opacity-80 cursor-not-allowed">
                    <div class="text-4xl mb-2">🏷️</div>
                    <div class="font-bold text-lg text-gray-800">หมวดหมู่อุปกรณ์</div>
                    <div class="text-sm text-gray-500 mt-1">จะเพิ่มในขั้นต่อไป</div>
                </a>

                <!-- สถานที่/ห้องเก็บ (จะทำภายหลัง) -->
                <a href="#"
                   class="block bg-white border border-pink-100 rounded-2xl p-6 shadow opacity-80 cursor-not-allowed">
                    <div class="text-4xl mb-2">🏢</div>
                    <div class="font-bold text-lg text-gray-800">สถานที่/ห้องเก็บ</div>
                    <div class="text-sm text-gray-500 mt-1">จะเพิ่มในขั้นต่อไป</div>
                </a>

                <!-- ผู้ใช้งาน (สำหรับ Admin) -->
                <a href="#"
                   class="block bg-white border border-pink-100 rounded-2xl p-6 shadow opacity-80 cursor-not-allowed">
                    <div class="text-4xl mb-2">🧑‍💼</div>
                    <div class="font-bold text-lg text-gray-800">ผู้ใช้งาน</div>
                    <div class="text-sm text-gray-500 mt-1">จะเพิ่มในขั้นต่อไป</div>
                </a>
            </div>

            <p class="mt-8 text-center text-gray-500">
                หน้านี้เป็นศูนย์รวมเมนูจัดการข้อมูลพื้นฐาน — เริ่มจาก “อุปกรณ์” ได้ก่อนเลย
            </p>
        </div>
    </div>
</x-app-layout>
