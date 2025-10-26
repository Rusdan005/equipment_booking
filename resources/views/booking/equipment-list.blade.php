<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-600 leading-tight flex justify-between items-center">
            <span>🎒 รายการอุปกรณ์ที่สามารถจองได้</span>
            
            {{-- ✨ [แก้ไข] ซ่อนปุ่มนี้ถ้าไม่ใช่ Admin/Staff --}}
            @if(auth()->check() && (auth()->user()->role == 'admin' || auth()->user()->role == 'staff'))
                {{-- ✨ [แก้ไข] เปลี่ยนลิงก์ไปหน้า route 'equipment.create' --}}
                <a href="{{ route('equipment.create') }}" 
                   class="px-4 py-2 bg-pink-500 text-white rounded-full hover:bg-pink-600 transition transform hover:scale-105">
                    ➕ เพิ่มอุปกรณ์ใหม่
                </a>
            @endif
        </h2>
    </x-slot>

    <div class="py-8 bg-gradient-to-b from-pink-50 via-white to-pink-100 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-2xl border border-pink-200 p-6">
                
                {{-- ✅ ถ้าไม่มีอุปกรณ์ --}}
                @if($equipments->isEmpty())
                    <p class="text-gray-500 text-center py-6">ยังไม่มีอุปกรณ์ที่พร้อมให้จอง</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($equipments as $eq)
                            <div class="bg-pink-50 border border-pink-200 rounded-xl p-4 shadow hover:shadow-lg transition flex flex-col justify-between">
                                
                                <div>
                                    {{-- ✨ [เพิ่ม] แสดงรูปภาพอุปกรณ์ --}}
                                    <div class="w-full h-40 bg-pink-100 rounded-lg mb-3 overflow-hidden">
                                        @if($eq->image)
                                            <img src="{{ asset('storage/' . $eq->image) }}" alt="{{ $eq->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center h-full text-pink-300">
                                                <span>📷 ไม่มีรูปภาพ</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- 🔹 ชื่ออุปกรณ์ --}}
                                    <h3 class="text-lg font-semibold text-pink-700 mb-2">
                                        {{ $eq->name }}
                                    </h3>
                                    <p class="text-gray-600 text-sm">ประเภท: {{ $eq->type ?? '-' }}</p>
                                    <p class="text-gray-600 text-sm">รหัสอุปกรณ์: {{ $eq->code ?? '-' }}</p>

                                    {{-- 🔹 สถานะ --}}
                                    <p class="text-gray-600 text-sm mb-3">
                                        สถานะ: 
                                        @if($eq->is_available)
                                            <span class="text-green-600 font-semibold">พร้อมใช้งาน ({{ $eq->available }} ชิ้น)</span>
                                        @else
                                            <span class="text-red-500 font-semibold">ไม่ว่าง</span>
                                        @endif
                                    </p>
                                </div>

                                {{-- 🔹 ปุ่มจัดการ --}}
                                <div class="flex justify-between items-center mt-4">
                                    {{-- ปุ่มจอง --}}
                                    <a href="{{ route('booking.create', ['equipment_id' => $eq->id]) }}" 
                                       class="px-3 py-2 bg-pink-500 text-white text-sm rounded-full hover:bg-pink-600 transition">
                                        🛒 จอง
                                    </a>

                                    {{-- ✨ [แก้ไข] ซ่อนปุ่ม Admin ถ้าไม่ใช่ Role ที่ถูกต้อง --}}
                                    @if(auth()->check() && (auth()->user()->role == 'admin' || auth()->user()->role == 'staff'))
                                        <div class="flex space-x-2">
                                            
                                            {{-- ปุ่มแก้ไข --}}
                                            {{-- ✨ [แก้ไข] เปลี่ยนลิงก์ไปหน้า route 'equipment.edit' --}}
                                            <a href="{{ route('equipment.edit', $eq->id) }}" 
                                               class="px-3 py-2 bg-yellow-400 text-white text-sm rounded-full hover:bg-yellow-500 transition">
                                                ✏️
                                            </a>

                                            {{-- ปุ่มลบ --}}
                                            <form action="{{ route('equipment.destroy', $eq->id) }}" method="POST"
                                                  onsubmit="return confirm('แน่ใจนะว่าจะลบอุปกรณ์นี้?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="px-3 py-2 bg-red-500 text-white text-sm rounded-full hover:bg-red-600 transition">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>