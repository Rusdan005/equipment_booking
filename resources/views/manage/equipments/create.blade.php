<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-600 leading-tight">
            ➕ เพิ่มอุปกรณ์ใหม่
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-8 mb-8">
        {{-- [ปรับแก้] ใช้ shadow-lg และเพิ่ม mb-8 --}}
        <div class="bg-white p-8 rounded-2xl border border-pink-200 shadow-lg">
            
            <!-- แสดงข้อผิดพลาด (Validation Errors) -->
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                    <strong class="font-bold">โอ๊ะ! เกิดข้อผิดพลาด!</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- ✨ [แก้ไข] เพิ่ม enctype สำหรับอัปโหลดไฟล์ -->
            <form action="{{ route('equipments.store') }}" method="POST" class="space-y-5" enctype="multipart/form-data">
                @csrf
                
                <!-- ชื่ออุปกรณ์ -->
                <div>
                    <label for="name" class="block text-gray-700 font-semibold mb-1">ชื่ออุปกรณ์</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400 shadow-sm" required>
                </div>

                <!-- รหัสอุปกรณ์ -->
                <div>
                    <label for="serial_number" class="block text-gray-700 font-semibold mb-1">รหัสอุปกรณ์ (Serial Number)</label>
                    <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number') }}" class="w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400 shadow-sm" placeholder="เช่น 22, 23 (ถ้ามี)">
                </div>

                <!-- ประเภท -->
                <div>
                    <label for="type" class="block text-gray-700 font-semibold mb-1">ประเภท</label>
                    <input type="text" name="type" id="type" value="{{ old('type') }}" class="w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400 shadow-sm" required placeholder="เช่น กีฬา, อุปกรณ์ไฟฟ้า, ห้อง">
                </div>

                <!-- จำนวนทั้งหมด -->
                <div>
                    <label for="total" class="block text-gray-700 font-semibold mb-1">จำนวนทั้งหมด (Total)</label>
                    <input type="number" name="total" id="total" value="{{ old('total', 1) }}" min="0" class="w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400 shadow-sm" required>
                </div>

                <!-- รายละเอียด -->
                <div>
                    <label for="description" class="block text-gray-700 font-semibold mb-1">รายละเอียด (Description)</label>
                    <textarea name="description" id="description" rows="3" class="w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400 shadow-sm">{{ old('description') }}</textarea>
                </div>

                <!-- ✨ [แก้ไข] เพิ่มช่องอัปโหลดรูปภาพ (ดีไซน์สวยงาม) -->
                <div>
                    <label for="image" class="block text-gray-700 font-semibold mb-1">รูปภาพ</label>
                    <input type="file" name="image" id="image" class="w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-pink-50 file:text-pink-700
                        hover:file:bg-pink-100 cursor-pointer
                    ">
                </div>

                <!-- ✨ [แก้ไข] เพิ่มปุ่ม "ยกเลิก" และจัดสไตล์ปุ่มใหม่ -->
                <div class="flex justify-end space-x-4 pt-4">
                    <a href="{{ route('equipments.index') }}" class="px-8 py-2 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 border border-gray-300 transition transform hover:scale-105 shadow-md">
                        ยกเลิก
                    </a>
                    <button type="submit" class="px-8 py-2 bg-pink-500 text-white rounded-full hover:bg-pink-600 transition transform hover:scale-105 shadow-md">
                        บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

