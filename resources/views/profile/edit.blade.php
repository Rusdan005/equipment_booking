<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-600 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-pink-50 via-white to-pink-100 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 🔹 ข้อมูลโปรไฟล์ --}}
            <div class="bg-white shadow-lg rounded-2xl p-8 border border-pink-100">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Profile Information</h3>
                <p class="text-gray-600 mb-6">
                    Update your account's profile information and email address.
                </p>

                <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                    @csrf
                    @method('patch')

                    {{-- ชื่อ --}}
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text"
                            class="block mt-1 w-full rounded-lg border-gray-300 focus:border-pink-500 focus:ring-pink-500"
                            :value="old('name', $user->name)" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    {{-- อีเมล --}}
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email"
                            class="block mt-1 w-full rounded-lg border-gray-300 focus:border-pink-500 focus:ring-pink-500"
                            :value="old('email', $user->email)" required autocomplete="username" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <x-primary-button
                            class="bg-gray-800 hover:bg-gray-900 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-[1.02]">
                            SAVE
                        </x-primary-button>

                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition
                               x-init="setTimeout(() => show = false, 2000)"
                               class="text-sm text-gray-500">{{ __('Saved.') }}</p>
                        @endif
                    </div>
                </form>
            </div>

            {{-- 🔸 เปลี่ยนรหัสผ่าน --}}
            <div class="bg-white shadow-lg rounded-2xl p-8 border border-pink-100">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Update Password</h3>
                <p class="text-gray-600 mb-6">
                    Ensure your account is using a long, random password to stay secure.
                </p>

                <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    @method('put')

                    {{-- รหัสผ่านปัจจุบัน --}}
                    <div>
                        <x-input-label for="current_password" :value="__('Current Password')" />
                        <x-text-input id="current_password" name="current_password" type="password"
                            class="block mt-1 w-full rounded-lg border-gray-300 focus:border-pink-500 focus:ring-pink-500"
                            autocomplete="current-password" />
                        <x-input-error class="mt-2" :messages="$errors->updatePassword->get('current_password')" />
                    </div>

                    {{-- รหัสผ่านใหม่ --}}
                    <div>
                        <x-input-label for="password" :value="__('New Password')" />
                        <x-text-input id="password" name="password" type="password"
                            class="block mt-1 w-full rounded-lg border-gray-300 focus:border-pink-500 focus:ring-pink-500"
                            autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password')" />
                    </div>

                    {{-- ยืนยันรหัสผ่าน --}}
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                            class="block mt-1 w-full rounded-lg border-gray-300 focus:border-pink-500 focus:ring-pink-500"
                            autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password_confirmation')" />
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <x-primary-button
                            class="bg-gray-800 hover:bg-gray-900 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-[1.02]">
                            SAVE
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- 🔻 ลบบัญชีผู้ใช้ --}}
            <div class="bg-white shadow-lg rounded-2xl p-8 border border-pink-100">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Delete Account</h3>
                <p class="text-gray-600 mb-6">
                    Once your account is deleted, all of its resources and data will be permanently deleted.
                    Before deleting your account, please download any data or information that you wish to retain.
                </p>

                <form method="post" action="{{ route('profile.destroy') }}" class="mt-6">
                    @csrf
                    @method('delete')

                    <x-danger-button
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-[1.02]"
                        onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                        DELETE ACCOUNT
                    </x-danger-button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
