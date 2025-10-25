{{-- resources/views/manage/bookings/review/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-500 leading-tight flex items-center gap-2">
            📝 รายการคำขอรอพิจารณา
        </h2>
    </x-slot>

    <div class="py-6 bg-gradient-to-b from-pink-50 via-white to-pink-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/95 shadow-md rounded-2xl p-6 border border-pink-100">

                {{-- ✅ แจ้งเตือน --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        ❌ {{ session('error') }}
                    </div>
                @endif

                {{-- 🧾 ตารางรายการคำขอ --}}
                @if($bookings->isEmpty())
                    <div class="text-center py-16 text-gray-500 text-lg">
                        🩷 ยังไม่มีคำขอที่รอพิจารณาในขณะนี้
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                            <thead class="bg-pink-200 text-pink-900 uppercase tracking-wide text-center">
                                <tr>
                                    <th class="border px-4 py-3">#</th>
                                    <th class="border px-4 py-3">ชื่อผู้จอง</th>
                                    <th class="border px-4 py-3">ชื่ออุปกรณ์</th>
                                    <th class="border px-4 py-3">วันที่ยืม</th>
                                    <th class="border px-4 py-3">วันที่คืน</th>
                                    <th class="border px-4 py-3">เวลามารับ</th>
                                    <th class="border px-4 py-3">เวลาคืน</th>
                                    <th class="border px-4 py-3 text-center">ดำเนินการ</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-pink-50 text-center text-gray-700">
                                @foreach($bookings as $booking)
                                    <tr class="hover:bg-pink-50 transition duration-150">
                                        <td class="border px-4 py-3">{{ $loop->iteration }}</td>
                                        <td class="border px-4 py-3">{{ optional($booking->user)->name ?? '-' }}</td>
                                        <td class="border px-4 py-3">{{ optional($booking->equipment)->name ?? '-' }}</td>
                                        <td class="border px-4 py-3">
                                            {{ $booking->borrow_date ? \Carbon\Carbon::parse($booking->borrow_date)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="border px-4 py-3">
                                            {{ $booking->return_date ? \Carbon\Carbon::parse($booking->return_date)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="border px-4 py-3">
                                            {{ $booking->pickup_time ? \Carbon\Carbon::parse($booking->pickup_time)->format('H:i') : '-' }}
                                        </td>
                                        <td class="border px-4 py-3">
                                            {{ $booking->return_time ? \Carbon\Carbon::parse($booking->return_time)->format('H:i') : '-' }}
                                        </td>
                                        <td class="border px-4 py-3 text-center">
                                            <a href="{{ route('manage.bookings.review.show', $booking->id) }}" 
                                               class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-full font-medium shadow transition duration-200">
                                                👀 ดูรายละเอียด
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- 🔹 pagination --}}
                    <div class="mt-6">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
