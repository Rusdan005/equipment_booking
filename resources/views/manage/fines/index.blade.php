<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#FF69B4] leading-tight flex items-center gap-2">
            💰 การจัดการค่าปรับ (Fines Management)
        </h2>
    </x-slot>

    <div class="py-8 bg-gradient-to-b from-pink-50 to-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ✅ ฟอร์มค้นหาและกรอง --}}
            <form method="GET" class="mb-6 flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="🔍 ค้นหาชื่อผู้ใช้..."
                       class="border rounded-lg px-4 py-2 w-64 focus:ring-2 focus:ring-pink-300">
                
                <select name="status" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-300">
                    <option value="">-- สถานะทั้งหมด --</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>ยังไม่ชำระ</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>ชำระแล้ว</option>
                </select>

                <button class="bg-[#FF69B4] text-white px-5 py-2 rounded-lg shadow hover:scale-105 transition">
                    กรองข้อมูล
                </button>
            </form>

            {{-- ✅ ตารางแสดงรายการค่าปรับ --}}
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <table class="min-w-full text-center border">
                    <thead class="bg-pink-100 text-[#FF69B4] uppercase text-sm font-semibold">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">ชื่อผู้ใช้</th>
                            <th class="px-4 py-3">รหัสจอง</th>
                            <th class="px-4 py-3">จำนวนเงิน (บาท)</th>
                            <th class="px-4 py-3">สาเหตุ</th>
                            <th class="px-4 py-3">สถานะ</th>
                            <th class="px-4 py-3">วันที่ครบกำหนด</th>
                            <th class="px-4 py-3">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse ($fines as $fine)
                            <tr class="border-t hover:bg-pink-50 transition">
                                <td class="py-2">{{ $fine->id }}</td>
                                <td>{{ $fine->user->name ?? '-' }}</td>
                                <td>#{{ $fine->booking_id }}</td>
                                <td class="font-bold text-pink-600">{{ number_format($fine->amount, 2) }}</td>
                                <td>{{ $fine->reason ?? '-' }}</td>
                                <td>
                                    @if($fine->status === 'paid')
                                        <span class="px-3 py-1 rounded-full text-white bg-green-500 text-xs">ชำระแล้ว</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-white bg-red-500 text-xs">ยังไม่ชำระ</span>
                                    @endif
                                </td>
                                <td>{{ $fine->due_date ?? '-' }}</td>
                                <td>
                                    @if($fine->status === 'unpaid')
                                        <form action="{{ route('manage.fines.markPaid', $fine->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                class="bg-green-500 hover:bg-green-600 text-white text-sm px-4 py-1 rounded-lg transition">
                                                ✅ ชำระแล้ว
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-4 text-gray-400">ไม่พบข้อมูลค่าปรับ</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $fines->links() }}</div>
        </div>
    </div>
</x-app-layout>
