<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-700 leading-tight">
            📊 แดชบอร์ดผู้ดูแลระบบ
        </h2>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-pink-50 to-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- ✅ Card 1 -->
                <div class="bg-white rounded-2xl shadow-lg border border-pink-100 p-6 hover:shadow-pink-200 transition">
                    <div class="text-pink-500 text-4xl mb-2">🧰</div>
                    <h3 class="font-bold text-lg text-gray-700">จำนวนอุปกรณ์ทั้งหมด</h3>
                    <p class="text-3xl font-semibold text-pink-600 mt-2">
                        {{ \App\Models\Equipment::count() }}
                    </p>
                </div>

                <!-- ✅ Card 2 -->
                <div class="bg-white rounded-2xl shadow-lg border border-pink-100 p-6 hover:shadow-pink-200 transition">
                    <div class="text-pink-500 text-4xl mb-2">🧑‍💼</div>
                    <h3 class="font-bold text-lg text-gray-700">จำนวนผู้ใช้งานทั้งหมด</h3>
                    <p class="text-3xl font-semibold text-pink-600 mt-2">
                        {{ \App\Models\User::count() }}
                    </p>
                </div>

                <!-- ✅ Card 3 -->
                <div class="bg-white rounded-2xl shadow-lg border border-pink-100 p-6 hover:shadow-pink-200 transition">
                    <div class="text-pink-500 text-4xl mb-2">📅</div>
                    <h3 class="font-bold text-lg text-gray-700">คำขอรอพิจารณา</h3>
                    <p class="text-3xl font-semibold text-pink-600 mt-2">
                        {{ \App\Models\Booking::where('status', 'pending')->count() }}
                    </p>
                </div>

            </div>

            <div class="mt-10 text-center">
                <p class="text-gray-500">ระบบจองและยืมอุปกรณ์ — แผงควบคุมผู้ดูแล 💼</p>
            </div>
        </div>
    </div>
</x-app-layout>
