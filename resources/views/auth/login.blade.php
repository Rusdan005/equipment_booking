<x-guest-layout>
    {{-- หัวข้อ: เข้าสู่ระบบ (ใช้ Primary Pink) --}}
    <h2 class="text-4xl font-extrabold text-center mb-8 text-[#FF69B4] drop-shadow-md">
        เข้าสู่ระบบ
    </h2>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            {{-- Label --}}
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold" /> 
            
            {{-- Input: เน้น Border/Focus Ring เป็นสีชมพูหลัก --}}
            <x-text-input 
                id="email" 
                class="block mt-1 w-full border-pink-300 focus:border-[#FF69B4] focus:ring-[#FF69B4] rounded-lg bg-white/70 shadow-inner" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username" 
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            {{-- Label --}}
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />

            {{-- Input: เน้น Border/Focus Ring เป็นสีชมพูหลัก --}}
            <x-text-input 
                id="password" 
                class="block mt-1 w-full border-pink-300 focus:border-[#FF69B4] focus:ring-[#FF69B4] rounded-lg bg-white/70 shadow-inner"
                type="password"
                name="password"
                required 
                autocomplete="current-password" 
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                {{-- 💖 Checkbox: เปลี่ยนสีเป็นสีชมพูหลัก --}}
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#FF69B4] shadow-sm focus:ring-[#FF69B4] w-5 h-5" name="remember">
                <span class="ms-2 text-sm text-gray-600">
                    {{ __('Remember me') }}
                </span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6">
            @if (Route::has('password.request'))
                {{-- ลิงก์ลืมรหัสผ่าน: เปลี่ยนสี Hover และ Focus Ring เป็นสีชมพู --}}
                <a class="underline text-sm text-gray-600 hover:text-[#FF69B4] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF69B4] transition-colors" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            {{-- 💖 ปุ่ม Log In: เปลี่ยนเป็นสีชมพูหลัก (Primary Pink) --}}
            <x-primary-button class="ms-4 px-6 py-2 bg-[#FF69B4] hover:bg-[#cc5490] focus:ring-[#FF69B4] shadow-lg hover:shadow-xl transition-all rounded-full font-bold">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>