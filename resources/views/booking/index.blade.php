<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-700 leading-tight flex items-center gap-2">
            📋 รายการอุปกรณ์ที่เปิดให้จองใช้งาน
        </h2>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-pink-50 to-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/95 backdrop-blur shadow-lg sm:rounded-2xl p-8 border border-pink-100">

                {{-- ✅ แจ้งเตือนเมื่อดำเนินการสำเร็จ --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-6 shadow-sm">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                {{-- ⚠️ หากไม่มีข้อมูลอุปกรณ์ --}}
                @if($equipments->isEmpty())
                    <div class="text-center py-12 text-gray-500 text-lg">
                        🚫 ขณะนี้ยังไม่มีรายการอุปกรณ์ในระบบ
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 shadow-sm rounded-lg overflow-hidden text-sm">
                            <thead class="bg-pink-200 text-pink-900 uppercase tracking-wide text-center">
                                <tr>
                                    <th class="border px-4 py-3 w-12">ลำดับ</th>
                                    <th class="border px-4 py-3">ชื่ออุปกรณ์</th>
                                    <th class="border px-4 py-3">ประเภท</th>
                                    <th class="border px-4 py-3">สถานะการใช้งาน</th>
                                    <th class="border px-4 py-3">การดำเนินการ</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-pink-50">
                                @foreach($equipments as $index => $item)
                                    <tr class="hover:bg-pink-50 transition duration-150 text-gray-700">
                                        <td class="border px-4 py-3 text-center">{{ $index + 1 }}</td>
                                        <td class="border px-4 py-3 font-semibold text-gray-800">{{ $item->name }}</td>
                                        <td class="border px-4 py-3 text-center">{{ $item->type ?? '-' }}</td>

                                        {{-- สถานะ --}}
                                        <td class="border px-4 py-3 text-center">
                                            @if($item->is_available)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-green-700 bg-green-100">
                                                    ✅ พร้อมให้ยืมใช้งาน
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-gray-600 bg-gray-100">
                                                    ⛔ ไม่สามารถยืมได้
                                                </span>
                                            @endif
                                        </td>

                                        {{-- ปุ่มดำเนินการ --}}
                                        <td class="border px-4 py-3 text-center">
                                            @if($item->is_available)
                                                <form action="{{ route('booking.store') }}" method="POST" class="flex flex-col sm:flex-row items-center justify-center gap-2">
                                                    @csrf
                                                    <input type="hidden" name="equipment_id" value="{{ $item->id }}">
                                                    
                                                    <div class="flex flex-col sm:flex-row gap-2">
                                                        <input type="date" name="borrow_date"
                                                            class="border border-pink-200 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-pink-400 focus:outline-none"
                                                            required>
                                                        <input type="date" name="return_date"
                                                            class="border border-pink-200 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-pink-400 focus:outline-none"
                                                            required>
                                                    </div>

                                                    <input type="text" name="purpose"
                                                        placeholder="วัตถุประสงค์"
                                                        class="border border-pink-200 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-pink-400 focus:outline-none mt-2 sm:mt-0"
                                                        required>

                                                    <input type="text" name="location"
                                                        placeholder="สถานที่ใช้งาน (ถ้ามี)"
                                                        class="border border-pink-200 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-pink-400 focus:outline-none mt-2 sm:mt-0">

                                                    <button type="submit"
                                                        class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-full shadow-md transition duration-200 text-sm font-medium mt-2 sm:mt-0">
                                                        จองใช้งาน
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-sm">—</span>
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
