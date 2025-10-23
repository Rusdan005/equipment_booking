<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#FF69B4] leading-tight flex items-center gap-2">
            📅 กำหนดรับอุปกรณ์ของฉัน
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
        .badge-pending {
            background-color: #fff7cc;
            color: #946200;
        }
        .badge-approved {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>

    <div class="py-8 bg-gradient-to-b from-pink-50 via-white to-pink-100 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ถ้าไม่มีข้อมูล --}}
            @if($bookings->isEmpty())
                <div class="bg-white border border-pink-200 rounded-2xl p-10 text-center shadow-sm">
                    <p class="text-gray-500 text-lg">
                        🚫 ขณะนี้ยังไม่มีรายการที่รอรับอุปกรณ์
                    </p>
                </div>
            @else
                {{-- แสดงรายการ --}}
                <div class="grid gap-6">
                    @foreach($bookings as $b)
                        <div class="card p-6">
                            <div class="flex flex-col md:flex-row justify-between gap-4">
                                
                                {{-- ฝั่งซ้าย: รายละเอียดอุปกรณ์ --}}
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

                                {{-- ฝั่งขวา: วันที่และสถานะ --}}
                                <div class="text-right">
                                    <p class="text-gray-500 text-sm">📆 วันนัดรับ:</p>
                                    <p class="text-lg font-semibold text-[#FF69B4]">
                                        {{ optional($b->borrow_date)->format('d/m/Y') }}
                                    </p>

                                    <p class="text-gray-500 text-sm mt-2">🔁 กำหนดคืน:</p>
                                    <p class="text-base font-semibold text-[#E17055]">
                                        {{ optional($b->return_date)->format('d/m/Y') }}
                                    </p>

                                    <div class="mt-3">
                                        <span class="badge 
                                            @if($b->status === 'approved') badge-approved
                                            @elseif($b->status === 'pending') badge-pending
                                            @elseif($b->status === 'rejected') badge-rejected
                                            @else bg-gray-200 text-gray-600 @endif">
                                            {{ strtoupper($b->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- โค้ดรับอุปกรณ์ --}}
                            @if(!empty($b->pickup_code))
                                <div class="mt-5 bg-pink-50 border border-pink-200 text-pink-700 rounded-xl px-4 py-3 flex items-center gap-2">
                                    <span class="text-xl">🔑</span>
                                    <span>โค้ดรับอุปกรณ์ของคุณคือ:</span>
                                    <span class="font-mono font-bold text-[#FF69B4]">{{ $b->pickup_code }}</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
