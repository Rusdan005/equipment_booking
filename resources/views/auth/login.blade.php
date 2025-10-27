<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-b from-pink-50 to-white py-12">
        <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8 border border-pink-100">
            
            {{-- 🔹 หัวข้อ --}}
            <h2 class="text-3xl font-bold text-center text-pink-600 mb-2">เข้าสู่ระบบ</h2>
            <p class="text-gray-600 text-center mb-8">กรอกอีเมลและรหัสผ่านเพื่อเข้าสู่ระบบจอง–ยืม–คืนอุปกรณ์</p>

            {{-- 🩷 แสดงสถานะ --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            {{-- 🔸 ฟอร์มเข้าสู่ระบบ --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('อีเมล')" class="text-pink-700 font-semibold" />
                    <x-text-input id="email" class="block mt-1 w-full border-gray-300 focus:border-pink-500 focus:ring-pink-500 rounded-lg"
                                  type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('รหัสผ่าน')" class="text-pink-700 font-semibold" />
                    <x-text-input id="password" class="block mt-1 w-full border-gray-300 focus:border-pink-500 focus:ring-pink-500 rounded-lg"
                                  type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-6">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" 
                               class="rounded border-gray-300 text-pink-600 shadow-sm focus:ring-pink-400" 
                               name="remember">
                        <span class="ms-2 text-sm text-gray-600">จำฉันไว้ในระบบ</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" 
                           class="text-sm text-pink-500 hover:text-pink-700 font-medium transition duration-150">
                            ลืมรหัสผ่าน?
                        </a>
                    @endif
                </div>

                {{-- ปุ่มเข้าสู่ระบบ --}}
                <div class="flex flex-col items-center gap-3">
                    <x-primary-button class="w-full justify-center bg-[#FF69B4] hover:bg-[#ff3c9d] focus:ring-pink-500 text-white font-semibold rounded-full py-3 shadow-md transition duration-200">
                        {{ __('เข้าสู่ระบบ') }}
                    </x-primary-button>

                    <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-pink-600 transition duration-150">
                        ยังไม่มีบัญชี? <span class="font-semibold text-pink-600">สมัครสมาชิก</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>