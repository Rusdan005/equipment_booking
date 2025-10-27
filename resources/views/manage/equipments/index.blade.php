<x-app-layout>
    <!-- 1. ส่วนหัว (Header) -->
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('จัดการอุปกรณ์ (Admin)') }}
            </h2>
            <!-- 1.1 ปุ่มเพิ่มอุปกรณ์ -->
            <a href="{{ route('equipments.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                เพิ่มอุปกรณ์ใหม่
            </a>
        </div>
    </x-slot>

    <!-- 2. ส่วนเนื้อหา (Content Slot) -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- 2.1 แสดงข้อความ Alert (Success/Error) -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                         <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- 2.2 ตารางแสดงรายการอุปกรณ์ -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รูปภาพ</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่ออุปกรณ์</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รหัส</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ประเภท</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">คงเหลือ / ทั้งหมด</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">แก้ไข</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($equipments as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <!-- แสดงรูปภาพ (ถ้ามี) -->
                                            @if ($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-16 h-16 object-cover rounded-md">
                                            @else
                                                <span class="text-gray-400 text-sm">ไม่มีรูป</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $item->serial_number ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                {{ $item->type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $item->available }} / {{ $item->total }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <!-- ปุ่มแก้ไข -->
                                            <a href="{{ route('equipments.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-4">แก้ไข</a>
                                            
                                            <!-- ฟอร์มลบ -->
                                            <form action="{{ route('equipments.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบอุปกรณ์นี้?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">ลบ</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- กรณีไม่มีข้อมูล -->
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            ไม่พบข้อมูลอุปกรณ์
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                     <!-- 2.3 ส่วนแบ่งหน้า (Pagination) -->
                    <div class="mt-6">
                        {{ $equipments->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

