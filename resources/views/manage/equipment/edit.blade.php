<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-600 leading-tight">
            ✏️ แก้ไขอุปกรณ์
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-8 bg-white p-8 rounded-2xl border border-pink-200 shadow">
        <form action="{{ route('equipment.update', $equipment->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-gray-700 font-semibold mb-1">ชื่ออุปกรณ์</label>
                <input type="text" name="name" value="{{ $equipment->name }}" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400" required>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">ประเภท</label>
                <input type="text" name="type" value="{{ $equipment->type }}" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">รหัสอุปกรณ์</label>
                <input type="text" name="code" value="{{ $equipment->code }}" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_available" value="1" {{ $equipment->is_available ? 'checked' : '' }}>
                <span class="text-gray-700">พร้อมใช้งาน</span>
            </div>

            <div class="text-right">
                <button class="px-6 py-2 bg-pink-500 text-white rounded-full hover:bg-pink-600">บันทึกการแก้ไข</button>
            </div>
        </form>
    </div>
</x-app-layout>
