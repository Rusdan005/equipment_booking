{{-- resources/views/manage/bookings/pickup/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-500 leading-tight">
            📦 รายการคำขอที่อนุมัติแล้ว (รอมารับอุปกรณ์)
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6 border border-pink-100">

                {{-- 🔍 ช่องค้นหา --}}
                <form method="GET" class="flex items-center gap-3 mb-6">
                    <input type="text" name="code" value="{{ request('code') }}" 
                        placeholder="🔎 ค้นหาด้วยรหัสรับอุปกรณ์..."
                        class="w-full border border-pink-200 rounded-full px-4 py-2 focus:ring-pink-400 focus:border-pink-400">
                    <button type="submit" 
                        class="bg-pink-500 text-white px-5 py-2 rounded-full hover:bg-pink-600 transition">
                        ค้นหา
                    </button>
                </form>

                {{-- ✅ ตารางข้อมูล --}}
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-pink-100 text-pink-700">
                            <th class="py-2 px-4 border-b">#</th>
                            <th class="py-2 px-4 border-b">ผู้จอง</th>
                            <th class="py-2 px-4 border-b">อุปกรณ์</th>
                            <th class="py-2 px-4 border-b">รหัสรับ</th>
                            <th class="py-2 px-4 border-b">วันที่ยืม</th>
                            <th class="py-2 px-4 border-b text-center">ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-pink-50 transition">
                                <td class="py-2 px-4 border-b">{{ $loop->iteration }}</td>
                                <td class="py-2 px-4 border-b">{{ $booking->user->name ?? '-' }}</td>
                                <td class="py-2 px-4 border-b">{{ $booking->equipment->name ?? '-' }}</td>
                                <td class="py-2 px-4 border-b font-mono text-sm text-gray-600">
                                    {{ $booking->pickup_code ?? '-' }}
                                </td>
                                <td class="py-2 px-4 border-b">{{ $booking->borrow_date }}</td>
                                <td class="py-2 px-4 border-b text-center">
                                    <form action="{{ route('manage.bookings.pickup.confirm', $booking->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('ยืนยันว่ารับอุปกรณ์นี้แล้วใช่หรือไม่?');">
                                        @csrf
                                        <button type="submit" 
                                            class="bg-green-500 text-white px-4 py-1 rounded-full hover:bg-green-600 transition">
                                            ✅ ยืนยันรับแล้ว
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-gray-500">
                                    ไม่มีคำขอที่รอรับอุปกรณ์
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- 🔸 Pagination --}}
                <div class="mt-4">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
