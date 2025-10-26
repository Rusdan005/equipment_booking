<x-guest-layout>
    {{-- 🌟 พื้นหลัง Watermark (ต้องใช้ z-index: 0 ใน guest.blade.php) --}}
    {{-- สมมติว่าโค้ดนี้ถูกเพิ่มใน guest.blade.php เพื่อสร้าง Watermark --}}
    {{-- <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0"> 
        <span class="text-[15rem] font-extrabold text-pink-200 opacity-50 select-none transform rotate-3">YRU BOOKING</span>
    </div> --}}

    <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-pink-50 to-white py-12 relative z-10">
        {{-- 🔹 Container ฟอร์ม: ดูพรีเมียมขึ้น --}}
        <div class="w-full max-w-lg bg-white shadow-2xl rounded-3xl p-10 border border-pink-200 transform hover:scale-[1.01] transition-transform duration-300">
            
            {{-- ⭐️ โลโก้ (เพิ่มเข้ามา) --}}
            <div class="text-center mb-6">
                {{--  --}}
                <img src="{{ asset('images/logo.png') }}" alt="YRU Booking" class="mx-auto h-16 w-auto">
            </div>

            {{-- 🔹 หัวข้อ --}}
            <h2 class="text-4xl font-extrabold text-center text-[#FF69B4] mb-2 drop-shadow-sm">
                เข้าสู่ระบบ
            </h2>
            <p class="text-gray-500 text-center mb-10">
                กรอกอีเมลและรหัสผ่านเพื่อเข้าสู่ระบบจอง–ยืม–คืนอุปกรณ์
            </p>

            {{-- 🩷 แสดงสถานะ --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            {{-- 🔸 ฟอร์มเข้าสู่ระบบ --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-5">
                    <x-input-label for="email" :value="__('อีเมล')" class="text-pink-700 font-bold mb-1" />
                    <x-text-input id="email" 
                                class="block w-full border-pink-300 focus:border-[#FF69B4] focus:ring-[#FF69B4] 
                                       rounded-xl px-4 py-3 bg-pink-50/70 shadow-inner text-lg placeholder-gray-400"
                                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                </div>

                <div class="mb-5">
                    <x-input-label for="password" :value="__('รหัสผ่าน')" class="text-pink-700 font-bold mb-1" />
                    <x-text-input id="password" 
                                class="block w-full border-pink-300 focus:border-[#FF69B4] focus:ring-[#FF69B4] 
                                       rounded-xl px-4 py-3 bg-pink-50/70 shadow-inner text-lg placeholder-gray-400"
                                type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                </div>

                <div class="flex items-center justify-between mb-8">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" 
                               class="rounded border-gray-300 text-pink-600 shadow-sm focus:ring-pink-500 w-5 h-5" 
                               name="remember">
                        <span class="ms-2 text-base text-gray-600">จำฉันไว้ในระบบ</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" 
                           class="text-base text-pink-500 hover:text-pink-700 font-semibold transition duration-200 underline-offset-4 hover:underline">
                            ลืมรหัสผ่าน?
                        </a>
                    @endif
                </div>

                {{-- ปุ่มเข้าสู่ระบบ --}}
                <div class="flex flex-col items-center gap-4">
                    <x-primary-button 
                        class="w-full justify-center bg-[#FF69B4] hover:bg-[#ff3c9d] focus:ring-pink-500 text-white 
                               font-extrabold rounded-full py-3.5 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.01]">
                        {{ __('เข้าสู่ระบบ') }}
                    </x-primary-button>

                    <a href="{{ route('register') }}" class="text-base text-gray-600 hover:text-pink-600 transition duration-150">
                        ยังไม่มีบัญชี? <span class="font-extrabold text-[#FF69B4] hover:underline">สมัครสมาชิกที่นี่</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>