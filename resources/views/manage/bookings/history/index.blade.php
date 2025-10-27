{{-- resources/views/manage/bookings/history/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-500 leading-tight">
            📜 ประวัติการจองและยืมอุปกรณ์ทั้งหมด
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-xl shadow border border-pink-100">

                {{-- 🔍 ฟอร์มค้นหา --}}
                <form method="GET" class="flex flex-wrap items-center gap-3 mb-6">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="🔎 ค้นหาชื่อผู้ใช้หรือชื่ออุปกรณ์..."
                        class="w-72 border border-pink-200 rounded-full px-4 py-2 focus:ring-pink-400 focus:border-pink-400">

                    <select name="status" class="border border-pink-200 rounded-full px-3 py-2 text-gray-700 focus:ring-pink-400 focus:border-pink-400">
                        <option value="">-- ทุกสถานะ --</option>
                        <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>⏳ รอพิจารณา</option>
                        <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>✅ อนุมัติแล้ว</option>
                        <option value="picked_up" {{ request('status')=='picked_up' ? 'selected' : '' }}>📦 รับแล้ว</option>
                        <option value="overdue" {{ request('status')=='overdue' ? 'selected' : '' }}>🚨 เลยกำหนด</option>
                        <option value="returned" {{ request('status')=='returned' ? 'selected' : '' }}>📬 คืนแล้ว</option>
                        <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>❌ ถูกปฏิเสธ</option>
                    </select>

                    <button type="submit"
                        class="bg-pink-500 text-white px-4 py-2 rounded-full hover:bg-pink-600 transition">
                        ค้นหา
                    </button>
                </form>
                
                <hr class="mb-6 border-pink-100">

                {{-- 📋 ตารางข้อมูล --}}
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead>
                            <tr class="bg-pink-100 text-pink-700">
                                <th class="py-3 px-4 border-b text-center">#</th>
                                <th class="py-3 px-4 border-b">ผู้จอง</th>
                                <th class="py-3 px-4 border-b">อุปกรณ์</th>
                                <th class="py-3 px-4 border-b text-center">วันที่ยืม</th>
                                <th class="py-3 px-4 border-b text-center">วันที่คืน</th>
                                <th class="py-3 px-4 border-b text-center">สถานะ</th>
                                <th class="py-3 px-4 border-b text-center">การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                                <tr class="hover:bg-pink-50 transition">
                                    <td class="py-2 px-4 border-b text-center">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4 border-b">{{ $booking->user->name ?? '-' }}</td>
                                    <td class="py-2 px-4 border-b">{{ $booking->equipment->name ?? '-' }}</td>
                                    <td class="py-2 px-4 border-b text-center">{{ $booking->borrow_date }}</td>
                                    <td class="py-2 px-4 border-b text-center">{{ $booking->return_date ?? '-' }}</td>
                                    <td class="py-2 px-4 border-b text-center">
                                        @switch($booking->status)
                                            @case('pending')
                                                <span class="text-yellow-500 font-semibold">⏳ รอพิจารณา</span>
                                                @break
                                            @case('approved')
                                                <span class="text-blue-500 font-semibold">✅ อนุมัติแล้ว</span>
                                                @break
                                            @case('picked_up')
                                                <span class="text-green-600 font-semibold">📦 รับแล้ว</span>
                                                @break
                                            @case('overdue')
                                                {{-- **เพิ่มสถานะ "เลยกำหนด"** --}}
                                                <span class="text-red-600 font-semibold">🚨 เลยกำหนด</span> 
                                                @break
                                            @case('returned')
                                                <span class="text-gray-700 font-semibold">📬 คืนแล้ว</span>
                                                @break
                                            @case('rejected')
                                                <span class="text-red-500 font-semibold">❌ ถูกปฏิเสธ</span>
                                                @break
                                            @default
                                                <span class="text-gray-400">ไม่ทราบสถานะ</span>
                                        @endswitch
                                    </td>
                                    <td class="py-2 px-4 border-b text-center">
                                        <a href="{{ route('manage.bookings.review.show', $booking->id) }}"
                                            class="px-3 py-1 text-sm bg-pink-100 text-pink-600 rounded-full hover:bg-pink-200 transition">
                                            🔍 ดูรายละเอียด
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-4 text-center text-gray-500">
                                        ไม่มีข้อมูลการจองในระบบ
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ✅ Pagination --}}
                <div class="mt-4">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>