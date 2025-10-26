<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-600 leading-tight">
            ➕ เพิ่มอุปกรณ์ใหม่
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-8 bg-white p-8 rounded-2xl border border-pink-200 shadow">
        <form action="{{ route('equipment.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-gray-700 font-semibold mb-1">ชื่ออุปกรณ์</label>
                <input type="text" name="name" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400" required>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">ประเภท</label>
                <input type="text" name="type" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">รหัสอุปกรณ์</label>
                <input type="text" name="code" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_available" value="1" checked>
                <span class="text-gray-700">พร้อมใช้งาน</span>
            </div>

            <div class="text-right">
                <button class="px-6 py-2 bg-pink-500 text-white rounded-full hover:bg-pink-600">บันทึก</button>
            </div>
        </form>
    </div>
</x-app-layout>
