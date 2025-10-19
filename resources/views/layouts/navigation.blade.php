<style>
    /* 🎀 ปุ่มขอบชมพู พื้นขาว ตัวอักษรชมพู */
    .btn-outline {
        background: #fff;
        color: #FF69B4;
        border: 2px solid #FF69B4;
        border-radius: 9999px; /* กลม */
        font-weight: 500;
        transition: all 0.25s ease;
        box-shadow: 0 1px 2px rgba(255, 105, 180, 0.1);
    }
    .btn-outline:hover {
        background: #FF69B4;
        color: #fff;
        box-shadow: 0 4px 12px rgba(255, 105, 180, 0.3);
        transform: scale(1.05);
    }
</style>

<nav x-data="{ open: false }" class="bg-gradient-to-r from-pink-50 via-white to-pink-100 border-b border-pink-200 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- 🔹 โลโก้ -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/abc.jpg') }}" alt="Logo"
                        class="h-10 w-10 rounded-full ring-2 ring-[#FF69B4] shadow-sm hover:scale-105 transition duration-200">
                    <span class="hidden sm:block font-bold text-[#FF69B4] text-lg tracking-wide hover:text-[#ff3c9d] transition">
                        ระบบจองอุปกรณ์
                    </span>
                </a>
            </div>

            <!-- 🔹 เมนูหลัก -->
            <div class="hidden sm:flex items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="btn-outline px-4 py-2">
                    🏠 หน้าแรก
                </a>

                <a href="{{ route('booking.create') }}" class="btn-outline px-4 py-2">
                    ➕ จองอุปกรณ์ใหม่
                </a>

            

                <a href="#" class="btn-outline px-4 py-2">
                    📊 ตรวจสอบการคืน
                </a>
            </div>

            <!-- 🔹 โปรไฟล์ -->
            <div class="hidden sm:flex items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center space-x-2 px-4 py-2 bg-white border border-pink-200 rounded-full text-gray-700 font-semibold shadow-sm hover:bg-pink-50 hover:text-[#FF69B4] transition duration-200">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-[#FF69B4]" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="hover:text-[#FF69B4]">
                            ⚙️ ตั้งค่าโปรไฟล์
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="hover:text-red-600">
                                🚪 ออกจากระบบ
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- 🔹 Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="p-2 rounded-md text-[#FF69B4] hover:bg-pink-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- 🔹 เมนูมือถือ -->
    <div :class="{'block': open, 'hidden': !open}" class="sm:hidden bg-white border-t border-pink-100 shadow-inner">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                🏠 หน้าแรก
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('booking.index')" :active="request()->routeIs('booking.index')">
                🎒 รายการอุปกรณ์
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('booking.create')" :active="request()->routeIs('booking.create')">
                ➕ จองอุปกรณ์ใหม่
            </x-responsive-nav-link>
            <x-responsive-nav-link href="#">
                📊 รายงานการยืม
            </x-responsive-nav-link>
        </div>

        <!-- 🔹 ผู้ใช้ -->
        <div class="pt-4 pb-1 border-t border-pink-100 bg-pink-50">
            <div class="px-4">
                <div class="font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    ⚙️ ตั้งค่าโปรไฟล์
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        🚪 ออกจากระบบ
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
