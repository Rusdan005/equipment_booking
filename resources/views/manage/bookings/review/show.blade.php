{{-- resources/views/manage/bookings/review/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-500 leading-tight">
            📝 รายละเอียดคำขอจองอุปกรณ์
        </h2>
    </x-slot>

    <div class="py-8 bg-gradient-to-b from-pink-50 via-white to-pink-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-2xl p-8 border border-pink-200">

                {{-- 👤 ข้อมูลผู้จอง --}}
                <div class="mb-8 bg-pink-50 border border-pink-200 rounded-xl p-5">
                    <h3 class="text-lg font-semibold text-pink-600 mb-3">👤 ข้อมูลผู้จอง</h3>
                    <div class="space-y-2 text-gray-700">
                        <p><strong>ชื่อ:</strong> {{ $booking->user->name ?? '-' }}</p>
                        <p><strong>อีเมล:</strong> {{ $booking->user->email ?? '-' }}</p>
                        <p><strong>คณะ:</strong> {{ $booking->faculty ?? '-' }}</p>
                        <p><strong>สาขา:</strong> {{ $booking->major ?? '-' }}</p>
                    </div>
                </div>

                {{-- 🎒 ข้อมูลอุปกรณ์ --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-pink-600 mb-2">🎒 ข้อมูลอุปกรณ์</h3>
                    <div class="space-y-1 text-gray-700">
                        <p><strong>ชื่ออุปกรณ์:</strong> {{ $booking->equipment->name ?? '-' }}</p>
                        <p><strong>ประเภท:</strong> {{ $booking->equipment->type ?? '-' }}</p>
                    </div>
                </div>

                {{-- 📅 ข้อมูลการจอง --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-pink-600 mb-2">📅 รายละเอียดการจอง</h3>
                    <div class="space-y-1 text-gray-700">
                        <p><strong>วันที่ยืม:</strong> {{ \Carbon\Carbon::parse($booking->borrow_date)->format('d/m/Y') }}</p>
                        <p><strong>วันที่คืน:</strong> {{ \Carbon\Carbon::parse($booking->return_date)->format('d/m/Y') }}</p>
                        <p><strong>สถานที่ใช้งาน:</strong> {{ $booking->location ?? '-' }}</p>
                        <p><strong>วัตถุประสงค์:</strong> {{ $booking->purpose ?? '-' }}</p>
                        <p><strong>เวลามารับ:</strong> {{ $booking->pickup_time ? \Carbon\Carbon::parse($booking->pickup_time)->format('H:i') : '-' }}</p>
                        <p><strong>เวลาคืน:</strong> {{ $booking->return_time ? \Carbon\Carbon::parse($booking->return_time)->format('H:i') : '-' }}</p>
                        <p>
                            <strong>สถานะ:</strong>
                            <span class="font-bold 
                                @if($booking->status === 'pending') text-yellow-500 
                                @elseif($booking->status === 'approved') text-green-500 
                                @elseif($booking->status === 'rejected') text-red-500 
                                @elseif($booking->status === 'returned') text-green-600 
                                @endif">
                                {{ strtoupper($booking->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                {{-- 🧾 หลักฐานการคืนอุปกรณ์ --}}
                @if($booking->return_photo)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-pink-600 mb-3">🧾 หลักฐานการคืนอุปกรณ์</h3>
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                            <p class="text-green-700 font-medium mb-3">✅ ผู้ใช้ได้คืนอุปกรณ์เรียบร้อยแล้ว</p>
                            <img src="{{ asset('storage/' . $booking->return_photo) }}"
                                 alt="หลักฐานการคืนอุปกรณ์"
                                 class="rounded-xl border border-gray-200 shadow-md w-72">
                        </div>
                    </div>
                @endif

                {{-- ✅ ปุ่มอนุมัติ / ❌ ปฏิเสธ --}}
                @if($booking->status === 'pending')
                    <div class="flex items-center gap-4 mt-8">
                        <form action="{{ route('manage.bookings.review.approve', $booking->id) }}" method="POST" onsubmit="return confirm('ยืนยันการอนุมัติคำขอนี้หรือไม่?');">
                            @csrf
                            <button type="submit" 
                                class="px-6 py-2 border-2 border-green-500 text-green-600 rounded-full font-medium hover:bg-green-50 transition transform hover:scale-105">
                                ✅ อนุมัติ
                            </button>
                        </form>

                        <button 
                            onclick="document.getElementById('rejectModal').classList.remove('hidden')" 
                            class="px-6 py-2 border-2 border-red-500 text-red-600 rounded-full font-medium hover:bg-red-50 transition transform hover:scale-105">
                            ❌ ปฏิเสธ
                        </button>
                    </div>
                @else
                    <div class="mt-6 text-gray-500 italic">
                        ⚠️ คำขอนี้ได้รับการพิจารณาแล้ว ({{ strtoupper($booking->status) }})
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
    <div id="rejectModal" class="hidden fixed inset-0 flex items-center justify-center bg-black/40 z-50">
        <div class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-md border border-pink-200 animate-fade-in">
            <h3 class="text-lg font-semibold text-red-500 mb-4 flex items-center gap-2">
                <span class="text-2xl">⚠️</span> ปฏิเสธคำขอจอง
            </h3>

            <form action="{{ route('manage.bookings.review.reject', $booking->id) }}" method="POST">
                @csrf
                <label class="block mb-2 text-gray-700 font-medium">เหตุผลในการปฏิเสธ:</label>
                <textarea name="reject_reason" rows="3" required
                    class="w-full border border-pink-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-400 focus:outline-none"></textarea>

                <div class="flex justify-end gap-3 mt-5">
                    <button type="button"
                        onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="px-5 py-2 border border-gray-300 text-gray-600 rounded-full hover:bg-gray-100 transition">
                        ยกเลิก
                    </button>

                    <button type="submit"
                        class="px-5 py-2 border-2 border-red-500 text-red-600 rounded-full font-medium hover:bg-red-50 transform hover:scale-105 transition">
                        🚫 ยืนยันปฏิเสธ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fade-in { animation: fade-in 0.25s ease-out; }
    </style>
</x-app-layout>
