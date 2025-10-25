<style>
    /* 🎀 ปุ่มขอบชมพู พื้นขาว ตัวอักษรชมพู */
    .btn-outline {
        background: #fff;
        color: #FF69B4;
        border: 2px solid #FF69B4;
        border-radius: 9999px;
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

    /* 🌸 ปุ่มแดชบอร์ดผู้ดูแลระบบ (ตรงกลางบาร์) */
    .btn-admin-center {
        background: linear-gradient(90deg, #FF69B4 0%, #ff3c9d 100%);
        color: #fff;
        font-weight: 700;
        padding: 0.6rem 1.5rem;
        border-radius: 9999px;
        box-shadow: 0 6px 20px rgba(255, 105, 180, 0.4);
        transition: all 0.25s ease;
    }
    .btn-admin-center:hover {
        transform: scale(1.07);
        box-shadow: 0 10px 25px rgba(255, 105, 180, 0.55);
        filter: brightness(1.05);
    }
</style>

<nav x-data="{ open: false }" class="relative bg-gradient-to-r from-pink-50 via-white to-pink-100 border-b border-pink-200 shadow-md">

    <!-- 🔹 ปุ่มแดชบอร์ดตรงกลาง (เฉพาะแอดมิน/สตาฟ) -->
    @if(auth()->user() && in_array(auth()->user()->role, ['admin','staff']))
        <div class="absolute left-1/2 -translate-x-1/2 -top-5 z-20">
            <a href="{{ route('admin.dashboard') }}"
               class="btn-admin-center {{ request()->routeIs('admin.dashboard') ? 'ring-2 ring-white/60 ring-offset-2 ring-offset-pink-200' : '' }}">
                📊 เข้าสู่แดชบอร์ดผู้ดูแลระบบ
            </a>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
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

            <!-- 🔹 เมนูหลัก (Desktop) -->
            <div class="hidden sm:flex items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="btn-outline px-4 py-2">
                    🏠 หน้าแรก
                </a>

                {{-- ✅ เมนูเฉพาะ "ผู้ใช้ทั่วไป" --}}
                @cannot('manage-bookings')
                    <a href="{{ route('booking.create') }}" class="btn-outline px-4 py-2">
                        ➕ จองอุปกรณ์ใหม่
                    </a>
                    <a href="{{ route('pickups.mine') }}" class="btn-outline px-4 py-2">
                        📅 กำหนดรับอุปกรณ์ของฉัน
                    </a>
                @endcannot

                {{-- ✅ เมนูเฉพาะ "แอดมิน/สตาฟ" --}}
                @can('manage-bookings')
                    <a href="{{ route('manage.bookings.review.index') }}" class="btn-outline px-4 py-2">
                        📝 พิจารณาการจอง
                    </a>
                    {{-- 📦 ลบ “มารับอุปกรณ์” ออกแล้ว --}}
                    <a href="{{ route('manage.bookings.history.index') }}" class="btn-outline px-4 py-2">
                        📜 ประวัติการจองทั้งหมด
                    </a>
                    <a href="{{ route('booking.return.list') }}" class="btn-outline px-4 py-2">
                        📊 ตรวจสอบการคืน
                    </a>
                    <a href="{{ route('manage.fines.index') }}" class="btn-outline px-4 py-2">
                        💰 การคิดค่าปรับ
                    </a>
                    <a href="{{ route('manage.masterdata.index') }}" class="btn-outline px-4 py-2">
                        ⚙️ จัดการข้อมูลพื้นฐาน
                    </a>
                @endcan
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

            {{-- ✅ เฉพาะผู้ใช้ทั่วไป --}}
            @cannot('manage-bookings')
                <x-responsive-nav-link :href="route('booking.create')" :active="request()->routeIs('booking.create')">
                    ➕ จองอุปกรณ์ใหม่
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pickups.mine')" :active="request()->routeIs('pickups.mine')">
                    📅 กำหนดรับอุปกรณ์ของฉัน
                </x-responsive-nav-link>
            @endcannot

            {{-- ✅ เฉพาะแอดมิน/สตาฟ --}}
            @can('manage-bookings')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    📊 แดชบอร์ดผู้ดูแลระบบ
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('manage.bookings.review.index')" :active="request()->routeIs('manage.bookings.review.*')">
                    📝 พิจารณาการจอง
                </x-responsive-nav-link>
                {{-- 📦 ลบ “มารับอุปกรณ์” ออกแล้ว --}}
                <x-responsive-nav-link :href="route('manage.bookings.history.index')" :active="request()->routeIs('manage.bookings.history.*')">
                    📜 ประวัติการจองทั้งหมด
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('booking.return.list')">
                    📊 ตรวจสอบการคืน
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('manage.fines.index')">
                    💰 การคิดค่าปรับ
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('manage.masterdata.index')" :active="request()->routeIs('manage.masterdata.*')">
                    ⚙️ จัดการข้อมูลพื้นฐาน
                </x-responsive-nav-link>
            @endcan
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
