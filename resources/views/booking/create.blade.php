<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#FF69B4] leading-tight flex items-center gap-2">
            📦 รายการอุปกรณ์ทั้งหมด
        </h2>
    </x-slot>

    <style>
        .btn-primary {
            background: linear-gradient(90deg, #FF69B4, #ff3c9d);
            color: white;
            transition: 0.25s;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #ff3c9d, #FF69B4);
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(255, 105, 180, 0.3);
        }
        .modal-bg {
            background: rgba(0, 0, 0, 0.6);
        }
    </style>

    <div class="py-10 bg-gradient-to-b from-pink-50 to-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- 🔸 ปุ่มเมนู --}}
            <div class="flex gap-4 mb-8">
                <button class="px-6 py-3 rounded-lg font-semibold text-white shadow btn-primary">
                    รายการอุปกรณ์
                </button>
                <a href="{{ route('booking.index') }}"
                   class="px-6 py-3 rounded-lg font-semibold bg-white border border-gray-300 text-gray-600 hover:bg-gray-100 shadow">
                  รายการอุปกรณ์ที่เปิดให้จอง
                </a>
            </div>

            {{-- 🔍 ช่องค้นหา --}}
            <div class="flex flex-col sm:flex-row items-center gap-4 mb-8">
                <div class="relative w-full sm:w-3/4">
                    <input type="text" id="searchInput" placeholder="🔍 ค้นหาอุปกรณ์..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-700 focus:ring-2 focus:ring-pink-400 focus:border-pink-400">
                </div>
                <select id="filterType"
                        class="rounded-lg border border-gray-300 px-4 py-3 text-gray-700 focus:ring-2 focus:ring-pink-400">
                    <option value="">ทุกประเภท</option>
                    <option value="กีฬา">กีฬา</option>
                    <option value="ห้อง">ห้อง</option>
                    <option value="เครื่องครัว">เครื่องครัว</option>
                </select>
            </div>
            <a href="{{ route('manage.masterdata.index') }}" class="...">➕ เพิ่มอุปกรณ์ใหม่</a>

            {{-- 🎒 รายการอุปกรณ์ --}}
            <div id="equipmentList" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($equipments as $item)
                    @php
                        $imageMap = [
                            'กีฬา' => 'bool.jpg',
                            'ห้อง' => 'room.jpg',
                            'เครื่องครัว' => 'k.jpg',
                            'บาสเกตบอล' => 'bas.jpg',
                            'แบดมินตัน' => 'bat.jpg',
                            'ฟุตบอล' => 'foot.jpg',
                            'แฮนด์บอล' => 'hand.jpg',
                            'ปิงปอง' => 'pin.jpg',
                            'ตะกร้อ' => 'tak.jpg',
                            'วอลเลย์บอล' => 'wow.jpg',
                            'ปลั๊กไฟ' => 'power.jpg',
                            'โปรเจคเตอร์' => 'pro.jpg',
                        ];
                        $imageFile = $imageMap[$item->type] ?? 'default.jpg';
                    @endphp

                    <div class="bg-white rounded-2xl shadow-lg border hover:shadow-2xl transition overflow-hidden">
                        <div class="relative">
                            <img src="{{ $item->image ? asset('images/' . $item->image) : asset('images/' . $imageFile) }}"
                                alt="{{ $item->name }}"
                                class="w-full h-48 object-cover rounded-t-2xl">

                            <span class="absolute top-3 right-3 bg-white/90 text-gray-700 text-sm px-3 py-1 rounded-full shadow">
                                {{ $item->type ?? 'ไม่ระบุประเภท' }}
                            </span>
                        </div>

                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-800">{{ $item->name }}</h3>
                            <p class="text-gray-500 text-sm mt-1">{{ $item->description ?? '-' }}</p>

                            <p class="mt-3 text-gray-700 text-sm">
                                🧩 ว่าง: {{ $item->available ?? 0 }}/{{ $item->total ?? 0 }}
                            </p>
                            <div class="h-2 bg-gray-200 rounded-full mt-1">
                                <div class="h-2 bg-green-500 rounded-full"
                                     style="width: {{ (($item->available ?? 0)/max($item->total ?? 1,1))*100 }}%"></div>
                            </div>

                            <div class="text-center mt-5">
                                <button 
                                    onclick="openModal('{{ $item->id }}','{{ $item->name }}','{{ $item->available ?? 0 }}','{{ $item->total ?? 0 }}')" 
                                    class="btn-primary w-full inline-block px-4 py-2 rounded-lg font-semibold text-center">
                                   จองอุปกรณ์
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 🌸 Modal ฟอร์มจอง --}}
    <div id="bookingModal" class="hidden fixed inset-0 z-50 flex items-center justify-center modal-bg">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 p-6 relative">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>

            <h2 class="text-xl font-bold mb-2 text-pink-600">🎒 จองอุปกรณ์</h2>
            <p id="modalEquipmentName" class="text-gray-700 font-medium text-lg mb-3"></p>
            <div class="bg-pink-50 border border-pink-200 rounded-lg p-3 text-sm mb-5">
                <span id="modalAvailable" class="text-gray-600"></span>
            </div>

            {{-- ฟอร์ม --}}
            <form method="POST" action="{{ route('booking.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="equipment_id" id="modalEquipmentId">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold">ชื่อ-นามสกุล</label>
                        <input type="text" name="fullname" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-400" required>
                    </div>
                    <div>
                        <label class="text-sm font-semibold">อีเมล</label>
                        <input type="email" name="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-400" required>
                    </div>
                    <div>
                        <label class="text-sm font-semibold">เบอร์โทรศัพท์</label>
                        <input type="text" name="phone" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-400" required>
                    </div>

                    {{-- 🔸 เพิ่ม สาขา + คณะ --}}
                    <div>
                        <label class="text-sm font-semibold">สาขา</label>
                        <input type="text" name="major" placeholder="เช่น วิทยาการคอมพิวเตอร์" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-400" required>
                    </div>
                    <div>
                        <label class="text-sm font-semibold">คณะ</label>
                        <input type="text" name="faculty" placeholder="เช่น คณะวิทยาศาสตร์เทคโนโลยี" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-400" required>
                    </div>

                    <div>
                        <label class="text-sm font-semibold">จำนวนที่ต้องการ</label>
                        <input type="number" name="quantity" min="1" value="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-400" required>
                    </div>
                    <div>
                        <label class="text-sm font-semibold">วันที่เริ่มใช้</label>
                        <input type="date" name="borrow_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-400" required>
                    </div>
                    <div>
                        <label class="text-sm font-semibold">วันที่คืน</label>
                        <input type="date" name="return_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-400" required>
                    </div>

                    {{-- 🔸 เพิ่มเวลามารับ และเวลาส่งคืน --}}
                    <div>
                        <label class="text-sm font-semibold">เวลามารับ</label>
                        <input type="time" name="pickup_time" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-400" required>
                    </div>
                    <div>
                        <label class="text-sm font-semibold">เวลาส่งคืน</label>
                        <input type="time" name="return_time" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-400" required>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold">วัตถุประสงค์</label>
                    <textarea name="purpose" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-400" required></textarea>
                </div>

                <div class="text-center pt-3">
                    <button type="submit" class="btn-primary px-10 py-2 rounded-full font-semibold text-lg">
                        ✅ ยืนยันการจอง
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 🎯 Script เปิด/ปิด Modal --}}
    <script>
        function openModal(id, name, available, total) {
            document.getElementById('bookingModal').classList.remove('hidden');
            document.getElementById('modalEquipmentId').value = id;
            document.getElementById('modalEquipmentName').textContent = name;
            document.getElementById('modalAvailable').textContent = `อุปกรณ์ที่ว่าง ${available} จาก ${total} ชิ้น`;
        }
        function closeModal() {
            document.getElementById('bookingModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
