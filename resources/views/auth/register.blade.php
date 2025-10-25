<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-b from-pink-50 to-white py-12">
        <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8 border border-pink-100">
            
            {{-- 🔹 หัวข้อ --}}
            <h2 class="text-3xl font-bold text-center text-pink-600 mb-2">สมัครสมาชิก</h2>
            <p class="text-gray-600 text-center mb-8">กรอกข้อมูลเพื่อสร้างบัญชีผู้ใช้สำหรับระบบจอง–ยืม–คืนอุปกรณ์</p>

            {{-- 🔸 ฟอร์มสมัครสมาชิก --}}
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <x-input-label for="name" :value="__('ชื่อ - สกุล')" class="text-pink-700 font-semibold" />
                    <x-text-input id="name" class="block mt-1 w-full border-gray-300 focus:border-pink-500 focus:ring-pink-500 rounded-lg" 
                                  type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('อีเมล')" class="text-pink-700 font-semibold" />
                    <x-text-input id="email" class="block mt-1 w-full border-gray-300 focus:border-pink-500 focus:ring-pink-500 rounded-lg"
                                  type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('รหัสผ่าน')" class="text-pink-700 font-semibold" />
                    <x-text-input id="password" class="block mt-1 w-full border-gray-300 focus:border-pink-500 focus:ring-pink-500 rounded-lg"
                                  type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <x-input-label for="password_confirmation" :value="__('ยืนยันรหัสผ่าน')" class="text-pink-700 font-semibold" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full border-gray-300 focus:border-pink-500 focus:ring-pink-500 rounded-lg"
                                  type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-sm" />
                </div>

                {{-- ปุ่มสมัครสมาชิก --}}
                <div class="flex flex-col items-center gap-3">
                    <x-primary-button class="w-full justify-center bg-[#FF69B4] hover:bg-[#ff3c9d] focus:ring-pink-500 text-white font-semibold rounded-full py-3 shadow-md transition duration-200">
                        {{ __('สมัครสมาชิก') }}
                    </x-primary-button>

                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-pink-600 transition duration-150">
                        มีบัญชีอยู่แล้ว? <span class="font-semibold text-pink-600">เข้าสู่ระบบ</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
