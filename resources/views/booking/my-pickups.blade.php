<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#FF69B4] leading-tight flex items-center gap-2">
            📅 กำหนดรับ / คืนอุปกรณ์ของฉัน
        </h2>
    </x-slot>

    <style>
        .card {
            background: #fff;
            border: 2px solid #ffd6e9;
            border-radius: 1rem;
            box-shadow: 0 4px 10px rgba(255, 182, 193, 0.2);
            transition: 0.3s;
        }
        .card:hover {
            transform: scale(1.01);
            box-shadow: 0 6px 16px rgba(255, 105, 180, 0.3);
        }
        .badge {
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .badge-pending { background-color: #fff7cc; color: #946200; }
        .badge-approved { background-color: #d1fae5; color: #065f46; }
        .badge-picked { background-color: #e0f2fe; color: #0369a1; }
        .badge-returned { background-color: #e6ffe9; color: #15803d; }
        .badge-overdue { background-color: #fee2e2; color: #b91c1c; }
    </style>

    <div class="py-8 bg-gradient-to-b from-pink-50 via-white to-pink-100 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- 🔸 ถ้าไม่มีข้อมูล --}}
            @if($bookings->isEmpty())
                <div class="bg-white border border-pink-200 rounded-2xl p-10 text-center shadow-sm">
                    <p class="text-gray-500 text-lg">
                        🚫 ขณะนี้ยังไม่มีรายการจองหรือรายการคืนอุปกรณ์ของคุณ
                    </p>
                </div>
            @else
                {{-- 🔸 แสดงรายการอุปกรณ์ --}}
                <div class="grid gap-6">
                    @foreach($bookings as $b)
                        <div class="card p-6">
                            <div class="flex flex-col md:flex-row justify-between gap-4">
                                
                                {{-- 📦 รายละเอียดอุปกรณ์ --}}
                                <div>
                                    <h3 class="text-xl font-bold text-[#FF69B4]">
                                        🎒 {{ optional($b->equipment)->name ?? 'อุปกรณ์ไม่ระบุ' }}
                                    </h3>
                                    <p class="text-gray-600 mt-1">
                                        รหัสการจอง: <span class="font-mono">#{{ $b->id }}</span>
                                    </p>
                                    <p class="text-gray-500 text-sm mt-2">
                                        จุดประสงค์: {{ $b->purpose ?? '-' }}
                                    </p>
                                    <p class="text-gray-500 text-sm">
                                        สถานที่ใช้งาน: {{ $b->location ?? '-' }}
                                    </p>
                                </div>

                                {{-- 🕓 วันที่และสถานะ --}}
                                <div class="text-right">
                                    <p class="text-gray-500 text-sm">📆 วันนัดรับ:</p>
                                    <p class="text-lg font-semibold text-[#FF69B4]">
                                        {{ \Carbon\Carbon::parse($b->borrow_date)->format('d/m/Y') }}
                                    </p>

                                    <p class="text-gray-500 text-sm mt-2">🔁 กำหนดคืน:</p>
                                    <p class="text-base font-semibold text-[#E17055]">
                                        {{ \Carbon\Carbon::parse($b->return_date)->format('d/m/Y') }}
                                    </p>

                                    <div class="mt-3">
                                        <span class="badge
                                            @if($b->status === 'approved') badge-approved
                                            @elseif($b->status === 'pending') badge-pending
                                            @elseif($b->status === 'picked_up') badge-picked
                                            @elseif($b->status === 'returned') badge-returned
                                            @elseif($b->status === 'overdue') badge-overdue
                                            @else bg-gray-200 text-gray-600 @endif">
                                            {{ strtoupper($b->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- 🔑 โค้ดรับอุปกรณ์ --}}
                            @if(!empty($b->pickup_code))
                                <div class="mt-5 bg-pink-50 border border-pink-200 text-pink-700 rounded-xl px-4 py-3 flex items-center gap-2">
                                    <span class="text-xl">🔑</span>
                                    <span>โค้ดรับอุปกรณ์ของคุณคือ:</span>
                                    <span class="font-mono font-bold text-[#FF69B4]">{{ $b->pickup_code }}</span>
                                </div>
                            @endif

                            {{-- 🧾 ส่วนเพิ่ม: ฟอร์มคืนอุปกรณ์ --}}
                            @if(in_array($b->status, ['picked_up', 'overdue']))
                                <div class="mt-6 border-t border-pink-100 pt-5">
                                    <form action="{{ route('booking.return', $b->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                        @csrf
                                        @method('PUT')

                                        <label class="block text-sm font-medium text-gray-600">
                                            📸 อัปโหลดรูปภาพตอนคืนอุปกรณ์:
                                        </label>
                                        <input type="file" name="return_photo" accept="image/*" capture="camera"
                                            class="border border-pink-200 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-pink-400 focus:outline-none"
                                            required>

                                        <button type="submit"
                                            class="bg-[#FF69B4] hover:bg-[#ff4f9c] text-white px-4 py-2 rounded-full font-medium shadow transition duration-200">
                                            ✅ ยืนยันการคืนอุปกรณ์
                                        </button>
                                    </form>
                                </div>
                            @elseif($b->status === 'returned')
                                <div class="mt-5 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3">
                                    ✅ คืนอุปกรณ์เรียบร้อยแล้ว
                                    @if($b->return_photo)
                                        <div class="mt-3">
                                            <img src="{{ asset('storage/' . $b->return_photo) }}"
                                                 alt="หลักฐานการคืน"
                                                 class="rounded-lg border border-gray-200 shadow w-64">
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
