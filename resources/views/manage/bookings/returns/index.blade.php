<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#FF69B4] leading-tight flex items-center gap-2">
            📊 ตรวจสอบการคืนอุปกรณ์
        </h2>
    </x-slot>

    <div class="py-10 bg-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border border-pink-200">
                <div class="p-6 text-gray-800">

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-pink-600">รายการอุปกรณ์ที่คืนแล้ว</h3>
                        <input type="text" placeholder="🔍 ค้นหาชื่อผู้จองหรืออุปกรณ์..."
                               class="border border-pink-300 rounded-lg px-3 py-2 w-64 focus:ring-2 focus:ring-pink-400 focus:outline-none">
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-center border border-pink-200 rounded-lg">
                            <thead class="bg-pink-100 text-pink-700 font-semibold">
                                <tr>
                                    <th class="px-4 py-3 border">#</th>
                                    <th class="px-4 py-3 border">ผู้จอง</th>
                                    <th class="px-4 py-3 border">อุปกรณ์</th>
                                    <th class="px-4 py-3 border">วันที่ยืม</th>
                                    <th class="px-4 py-3 border">วันที่คืน</th>
                                    <th class="px-4 py-3 border">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                    <tr class="border-b hover:bg-pink-50 transition">
                                        <td class="py-3">{{ $loop->iteration }}</td>
                                        <td class="py-3">{{ $booking->user->name ?? '-' }}</td>
                                        <td class="py-3">{{ $booking->equipment->name ?? '-' }}</td>
                                        <td class="py-3">{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</td>
                                        <td class="py-3">{{ \Carbon\Carbon::parse($booking->returned_at)->format('d/m/Y') }}</td>
                                        <td class="py-3">
                                            @if($booking->status == 'returned')
                                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">คืนแล้ว</span>
                                            @elseif($booking->status == 'overdue')
                                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">เกินกำหนด</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-5 text-gray-500">ไม่มีข้อมูลการคืนในระบบ</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $bookings->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
