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
        .btn-secondary {
            background-color: #fce7f3; /* Pink-100 */
            color: #be185d; /* Pink-700 */
            transition: 0.2s;
        }
        .btn-secondary:hover {
            background-color: #fbcfe8; /* Pink-200 */
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
                
                {{-- 🔍 ฟอร์มค้นหา/กรอง --}}
                <form method="GET" class="flex flex-wrap items-center gap-3 mb-6">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="🔎 ค้นหาชื่อผู้ใช้/อุปกรณ์..."
                        class="w-72 border border-pink-200 rounded-full px-4 py-2 text-sm focus:ring-pink-400 focus:border-pink-400">
                    
                    <select name="filter" class="border border-pink-200 rounded-full px-3 py-2 text-gray-700 text-sm focus:ring-pink-400 focus:border-pink-400">
                        <option value="">-- ทุกรายการที่ถูกยืม --</option>
                        <option value="pending_return" {{ request('filter')=='pending_return' ? 'selected' : '' }}>รายการที่ยังไม่คืน</option>
                        <option value="overdue" {{ request('filter')=='overdue' ? 'selected' : '' }}>🚨 รายการเกินกำหนดคืน</option>
                        <option value="has_photo" {{ request('filter')=='has_photo' ? 'selected' : '' }}>มีหลักฐานการคืน</option>
                        <option value="returned_only" {{ request('filter')=='returned_only' ? 'selected' : '' }}>คืนแล้วทั้งหมด</option>
                    </select>

                    <button type="submit"
                        class="bg-pink-500 text-white px-4 py-2 rounded-full hover:bg-pink-600 transition text-sm">
                        ค้นหา
                    </button>
                    <a href="{{ route('booking.return.list') }}" class="btn-secondary px-4 py-2 rounded-full text-sm">
                        🔄 ล้าง/รีเฟรช
                    </a>
                </form>

                <hr class="mb-6 border-pink-100">

                {{-- 🧾 ตารางรายการคืนอุปกรณ์ --}}
                @if($bookings->isEmpty())
                    <div class="text-center py-16 text-gray-500 text-lg">
                        🩷 ไม่มีรายการอุปกรณ์ที่รอการตรวจสอบการคืนในขณะนี้
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 shadow-sm rounded-lg overflow-hidden text-sm">
                            <thead class="bg-pink-200 text-pink-900 uppercase tracking-wide text-center">
                                <tr>
                                    <th class="border px-4 py-3">ลำดับ</th>
                                    <th class="border px-4 py-3">ชื่อผู้ยืม</th>
                                    <th class="border px-4 py-3">ชื่ออุปกรณ์</th>
                                    <th class="border px-4 py-3">วันที่ยืม</th>
                                    <th class="border px-4 py-3">กำหนดคืน</th>
                                    <th class="border px-4 py-3">สถานะ</th>
                                    <th class="border px-4 py-3">หลักฐาน</th>
                                    <th class="border px-4 py-3">การดำเนินการ</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-pink-50 text-center">
                                @foreach($bookings as $index => $b)
                                    <tr class="hover:bg-pink-50 transition duration-150 text-gray-700">
                                        <td class="border px-4 py-3">{{ $bookings->firstItem() + $index }}</td>
                                        
                                        {{-- ข้อมูลผู้ยืม --}}
                                        <td class="border px-4 py-3 text-left">
                                            <div class="font-semibold text-gray-800">{{ optional($b->user)->name ?? 'ผู้ใช้ถูกลบ' }}</div>
                                            <div class="text-xs text-gray-500">{{ $b->faculty ?? '-' }} / {{ $b->major ?? '-' }}</div>
                                        </td>
                                        
                                        {{-- ข้อมูลอุปกรณ์ --}}
                                        <td class="border px-4 py-3 font-semibold text-gray-800 text-left">
                                            {{ optional($b->equipment)->name ?? 'อุปกรณ์ถูกลบ' }}
                                        </td>

                                        {{-- วันที่ยืมและกำหนดคืน --}}
                                        <td class="border px-4 py-3">
                                            {{ \Carbon\Carbon::parse($b->borrow_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="border px-4 py-3 text-gray-800 font-medium">
                                            {{ \Carbon\Carbon::parse($b->return_date)->format('d/m/Y') }}
                                        </td>

                                        {{-- ✅ สถานะ --}}
                                        <td class="border px-4 py-3">
                                            @if($b->status === 'returned')
                                                <span class="status-returned">✅ คืนแล้ว</span>
                                            @elseif(\Carbon\Carbon::parse($b->return_date)->isPast())
                                                <span class="status-overdue">🚨 เกินกำหนด</span>
                                            @else
                                                <span class="status-pending">📦 ยังไม่คืน</span>
                                            @endif
                                            
                                            {{-- แสดงเวลาที่ผู้ใช้แจ้งคืน ถ้ามี --}}
                                            @if($b->return_time)
                                                <div class="text-xs mt-1 text-blue-600 font-medium">
                                                    แจ้งคืน: {{ \Carbon\Carbon::parse($b->return_time)->format('H:i') }}
                                                </div>
                                            @endif
                                        </td>
                                        
                                        {{-- 📸 หลักฐานการคืน --}}
                                        <td class="border px-4 py-3">
                                            @if($b->return_photo)
                                                <a href="{{ asset('storage/' . $b->return_photo) }}" target="_blank"
                                                   class="btn-secondary px-3 py-1 rounded-full text-xs font-medium inline-flex items-center gap-1 hover:underline">
                                                    📷 ดูรูปภาพ
                                                </a>
                                            @else
                                                <span class="text-gray-400">ไม่มี</span>
                                            @endif
                                        </td>


                                        {{-- ⚙ ปุ่มดำเนินการ --}}
                                        <td class="border px-4 py-3">
                                            @if($b->status !== 'returned')
                                                <form action="{{ route('booking.return', $b->id) }}" method="POST"
                                                        onsubmit="return confirm('ยืนยันการคืนอุปกรณ์นี้หรือไม่? (จะถือว่าผู้ดูแลระบบเป็นผู้ตรวจสอบและรับคืนเอง)')">
                                                     @csrf
                                                     @method('PUT')
                                                     <button type="submit" class="btn-primary px-3 py-1 rounded-full text-xs font-medium">
                                                         ✅ ยืนยันการคืน
                                                     </button>
                                                 </form>
                                            @else
                                                <span class="text-gray-400">✔ คืนเรียบร้อยแล้ว</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- 📄 Pagination --}}
                    <div class="mt-4">
                        {{ $bookings->links() }}
                    </div>

                @endif
            </div>
        </div>
    </div>
</x-app-layout>