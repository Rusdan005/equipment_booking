{{-- resources/views/manage/bookings/review/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-500 leading-tight">
            📝 รายละเอียดคำขอจองอุปกรณ์
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-8 border border-pink-100">

                {{-- ข้อมูลผู้จอง --}}
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-pink-600 mb-2">👤 ข้อมูลผู้จอง</h3>
                    <p><strong>ชื่อ:</strong> {{ $booking->user->name ?? '-' }}</p>
                    <p><strong>อีเมล:</strong> {{ $booking->user->email ?? '-' }}</p>
                </div>

                {{-- ข้อมูลอุปกรณ์ --}}
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-pink-600 mb-2">🎒 ข้อมูลอุปกรณ์</h3>
                    <p><strong>ชื่ออุปกรณ์:</strong> {{ $booking->equipment->name ?? '-' }}</p>
                    <p><strong>ประเภท:</strong> {{ $booking->equipment->type ?? '-' }}</p>
                </div>

                {{-- ข้อมูลการจอง --}}
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-pink-600 mb-2">📅 รายละเอียดการจอง</h3>
                    <p><strong>วันที่ยืม:</strong> {{ $booking->borrow_date }}</p>
                    <p><strong>วันที่คืน:</strong> {{ $booking->return_date }}</p>
                    <p><strong>สถานที่ใช้งาน:</strong> {{ $booking->location }}</p>
                    <p><strong>วัตถุประสงค์:</strong> {{ $booking->purpose }}</p>
                    <p><strong>สถานะ:</strong> 
                        <span class="font-bold 
                            @if($booking->status === 'pending') text-yellow-500 
                            @elseif($booking->status === 'approved') text-green-500 
                            @elseif($booking->status === 'rejected') text-red-500 
                            @endif">
                            {{ strtoupper($booking->status) }}
                        </span>
                    </p>
                </div>

                {{-- ปุ่มอนุมัติ / ปฏิเสธ --}}
                @if($booking->status === 'pending')
                    <div class="flex items-center gap-4 mt-8">
                        {{-- ✅ อนุมัติ --}}
                        <form action="{{ route('manage.bookings.review.approve', $booking->id) }}" method="POST" onsubmit="return confirm('ยืนยันการอนุมัติคำขอนี้หรือไม่?');">
                            @csrf
                            <button type="submit" 
                                class="px-5 py-2 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-md transition">
                                ✅ อนุมัติ
                            </button>
                        </form>

                        {{-- ❌ ปฏิเสธ --}}
                        <button 
                            onclick="document.getElementById('rejectModal').classList.remove('hidden')" 
                            class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white rounded-full shadow-md transition">
                            ❌ ปฏิเสธ
                        </button>
                    </div>
                @else
                    <div class="mt-6 text-gray-500 italic">
                        ⚠️ คำขอนี้ได้รับการพิจารณาแล้ว ({{ $booking->status }})
                    </div>
                @endif

                {{-- 🔙 ปุ่มย้อนกลับ --}}
                <div class="mt-10">
                    <a href="{{ route('manage.bookings.review.index') }}"
                        class="text-pink-500 hover:text-pink-700 underline">
                        ← กลับไปหน้าพิจารณาการจอง
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔹 Modal สำหรับปฏิเสธ --}}
    <div id="rejectModal" class="hidden fixed inset-0 flex items-center justify-center bg-black/40">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md border border-pink-200">
            <h3 class="text-lg font-semibold text-red-600 mb-3">ปฏิเสธคำขอจอง</h3>
            <form action="{{ route('manage.bookings.review.reject', $booking->id) }}" method="POST">
                @csrf
                <label class="block mb-2 text-gray-700 font-medium">เหตุผลในการปฏิเสธ:</label>
                <textarea name="reject_reason" rows="3" required
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-pink-400 focus:border-pink-400"></textarea>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" 
                        onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="px-4 py-2 border rounded-md text-gray-600 hover:bg-gray-100">
                        ยกเลิก
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md">
                        ยืนยันปฏิเสธ
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
