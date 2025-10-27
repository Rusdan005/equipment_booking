<x-app-layout>
    <!-- 1. ส่วนหัว (Header) - ใช้ดีไซน์ธีมสีชมพูตามที่คุณต้องการ -->
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-600 leading-tight">
            ✏️ แก้ไขอุปกรณ์: {{ $equipment->name }}
        </h2>
    </x-slot>

    <!-- 2. ส่วนเนื้อหา (Content Slot) -->
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-pink-200 shadow">
                <div class="p-6 md:p-8 text-gray-900">

                    <!-- 2.1 แสดง Error (ถ้ามี) -->
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded-lg">
                            <strong class="font-bold">เกิดข้อผิดพลาด!</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- 2.2 ฟอร์มแก้ไขอุปกรณ์ -->
                    <!-- 
                        [แก้ไข] 1. แก้ route เป็น 'equipments.update' (เติม s)
                        [แก้ไข] 2. เพิ่ม enctype="multipart/form-data" สำหรับอัปโหลดรูป
                    -->
                    <form action="{{ route('equipments.update', $equipment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT') <!-- สำคัญ: บอก Laravel ว่านี่คือการ Update -->

                        <!-- ชื่ออุปกรณ์ -->
                        <div>
                            <label for="name" class="block text-gray-700 font-semibold mb-1">ชื่ออุปกรณ์</label>
                            <!-- ใช้ old() เพื่อดึงค่าที่กรอกผิดพลาด, ถ้าไม่มี ให้ดึงค่าจาก $equipment -->
                            <input type="text" name="name" id="name" value="{{ old('name', $equipment->name) }}" required
                                   class="w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400 shadow-sm">
                        </div>

                        <!-- รหัสอุปกรณ์ -->
                        <div>
                            <!-- [แก้ไข] 3. เปลี่ยน name="code" เป็น "serial_number" -->
                            <label for="serial_number" class="block text-gray-700 font-semibold mb-1">รหัสอุปกรณ์ (Serial Number)</label>
                            <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $equipment->serial_number) }}"
                                   class="w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400 shadow-sm"
                                   placeholder="เช่น T001, C002 (ไม่บังคับ)">
                        </div>

                        <!-- ประเภท -->
                        <div>
                            <label for="type" class="block text-gray-700 font-semibold mb-1">ประเภท</label>
                            <input type="text" name="type" id="type" value="{{ old('type', $equipment->type) }}" required
                                   class="w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400 shadow-sm"
                                   placeholder="เช่น กีฬา, อุปกรณ์ไฟฟ้า, ห้อง">
                        </div>

                        <!-- [เพิ่ม] 4. เพิ่มช่อง "จำนวนทั้งหมด" (Total) -->
                        <div>
                            <label for="total" class="block text-gray-700 font-semibold mb-1">จำนวนทั้งหมด (Total)</label>
                            <input type="number" name="total" id="total" value="{{ old('total', $equipment->total) }}" required min="0"
                                   class="w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400 shadow-sm">
                            <p class="mt-1 text-xs text-gray-500">
                                จำนวนที่ยืมได้ (Available): {{ $equipment->available }}
                                (หากแก้ไข Total ระบบจะคำนวณ Available ให้ใหม่)
                            </p>
                        </div>

                        <!-- [เพิ่ม] 5. เพิ่มช่อง "รายละเอียด" (Description) -->
                        <div>
                            <label for="description" class="block text-gray-700 font-semibold mb-1">รายละเอียด (Description)</label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400 shadow-sm">{{ old('description', $equipment->description) }}</textarea>
                        </div>

                        <!-- [เพิ่ม] 6. เพิ่มช่อง "อัปโหลดรูปภาพ" (Image) -->
                        <div>
                            <label for="image" class="block text-gray-700 font-semibold mb-1">เปลี่ยนรูปภาพ (Image)</label>
                            <!-- แสดงรูปเก่า -->
                            @if ($equipment->image)
                                <div class="my-2">
                                    <img src="{{ asset('storage/' . $equipment->image) }}" alt="{{ $equipment->name }}" class="w-32 h-32 object-cover rounded-md">
                                    <p class="mt-1 text-xs text-gray-500">รูปภาพปัจจุบัน</p>
                                </div>
                            @else
                                 <p class="my-2 text-xs text-gray-500">ไม่มีรูปภาพ</p>
                            @endif

                            <input type="file" name="image" id="image" accept="image/*"
                                   class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-pink-400">
                            <p class="mt-1 text-xs text-gray-500">เลือกไฟล์ใหม่เพื่ออัปเดต (ถ้าไม่ต้องการเปลี่ยน ให้เว้นว่างไว้)</p>
                        </div>
                        
                        <!-- 
                            [ลบ] 7. ลบช่อง is_available ออก 
                            (Controller จะคำนวณให้เอง)
                        -->

                        <!-- ปุ่ม Submit -->
                        <div class="flex justify-end space-x-4 pt-4">
                            <a href="{{ route('equipments.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 border border-gray-300">
                                ยกเลิก
                            </a>
                            <button type="submit" class="px-6 py-2 bg-pink-500 text-white rounded-full hover:bg-pink-600">
                                บันทึกการแก้ไข
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
