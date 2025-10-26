<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-700 leading-tight flex items-center gap-2">
            🔍 ตรวจสอบการคืนอุปกรณ์
        </h2>
    </x-slot>

    <style>
        .btn-primary {
            background: #FF69B4;
            color: white;
            transition: 0.25s;
        }
        .btn-primary:hover {
            background: #ff3c9d;
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(255, 105, 180, 0.3);
        }
        .status-returned {
            background: #D1FAE5;
            color: #047857;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
        }
        .status-pending {
            background: #FEF3C7;
            color: #B45309;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
        }
        .status-overdue {
            background: #FEE2E2;
            color: #B91C1C;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
        }
    </style>

    <div class="py-10 bg-gradient-to-b from-pink-50 to-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/95 backdrop-blur shadow-lg sm:rounded-2xl p-8 border border-pink-100">

                {{-- ✅ แจ้งเตือน --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-6 shadow-sm">
                        ✅ {{ session('success') }}
                    </div>
                @endif
                    
                {{-- 🧾 ตารางรายการคืนอุปกรณ์ --}}
                @if($bookings->isEmpty())
                    <div class="text-center py-16 text-gray-500 text-lg">
                        🩷 ยังไม่มีข้อมูลการยืมหรือคืนอุปกรณ์ในขณะนี้
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 shadow-sm rounded-lg overflow-hidden text-sm">
                            <thead class="bg-pink-200 text-pink-900 uppercase tracking-wide text-center">
                                <tr>
                                    <th class="border px-4 py-3">ลำดับ</th>
                                    <th class="border px-4 py-3">ชื่อผู้ยืม</th>
                                    <th class="border px-4 py-3">ชื่ออุปกรณ์</th>
                                    <th class="border px-4 py-3">คณะ</th>
                                    <th class="border px-4 py-3">สาขา</th>
                                    <th class="border px-4 py-3">วันที่ยืม</th>
                                    <th class="border px-4 py-3">กำหนดคืน</th>
                                    <th class="border px-4 py-3">เวลามารับ</th>
                                    <th class="border px-4 py-3">เวลาคืน</th>
                                    <th class="border px-4 py-3">สถานะ</th>
                                    <th class="border px-4 py-3">การดำเนินการ</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-pink-50 text-center">
                                @foreach($bookings as $index => $b)
                                    <tr class="hover:bg-pink-50 transition duration-150 text-gray-700">
                                        <td class="border px-4 py-3">{{ $index + 1 }}</td>
                                        <td class="border px-4 py-3">{{ optional($b->user)->name ?? '-' }}</td>
                                        <td class="border px-4 py-3 font-semibold text-gray-800">
                                            {{ optional($b->equipment)->name ?? '-' }}
                                        </td>
                                        <td class="border px-4 py-3">{{ $b->faculty ?? '-' }}</td>
                                        <td class="border px-4 py-3">{{ $b->major ?? '-' }}</td>
                                        <td class="border px-4 py-3">{{ \Carbon\Carbon::parse($b->borrow_date)->format('d/m/Y') }}</td>
                                        <td class="border px-4 py-3">{{ \Carbon\Carbon::parse($b->return_date)->format('d/m/Y') }}</td>
                                        <td class="border px-4 py-3">
                                            {{ $b->pickup_time ? \Carbon\Carbon::parse($b->pickup_time)->format('H:i') : '-' }}
                                        </td>
                                        <td class="border px-4 py-3">
                                            {{ $b->return_time ? \Carbon\Carbon::parse($b->return_time)->format('H:i') : '-' }}
                                        </td>

                                        {{-- ✅ สถานะ --}}
                                        <td class="border px-4 py-3">
                                            @if($b->status === 'returned')
                                                <span class="status-returned">คืนแล้ว</span>
                                            @elseif(\Carbon\Carbon::parse($b->return_date)->isPast() && $b->status !== 'returned')
                                                <span class="status-overdue">เกินกำหนด</span>
                                            @else
                                                <span class="status-pending">ยังไม่คืน</span>
                                            @endif
                                        </td>

                                        {{-- ⚙ ปุ่มดำเนินการ --}}
                                        <td class="border px-4 py-3">
                                            @if($b->status !== 'returned')
                                                <form action="{{ route('booking.return', $b->id) }}" method="POST" onsubmit="return confirm('ยืนยันการคืนอุปกรณ์นี้หรือไม่?')">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn-primary px-4 py-2 rounded-full text-sm">
                                                        ✅ ทำเครื่องหมายว่าคืนแล้ว
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400">✔ คืนเรียบร้อย</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
