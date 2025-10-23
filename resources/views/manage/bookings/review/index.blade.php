{{-- resources/views/manage/bookings/review/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-500 leading-tight">
            📝 รายการคำขอรอพิจารณา
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">

                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-pink-100 text-pink-700">
                            <th class="py-2 px-4 border-b">#</th>
                            <th class="py-2 px-4 border-b">ผู้จอง</th>
                            <th class="py-2 px-4 border-b">อุปกรณ์</th>
                            <th class="py-2 px-4 border-b">วันที่ยืม</th>
                            <th class="py-2 px-4 border-b">วันที่คืน</th>
                            <th class="py-2 px-4 border-b text-center">ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-pink-50 transition">
                                <td class="py-2 px-4 border-b">{{ $loop->iteration }}</td>
                                <td class="py-2 px-4 border-b">{{ $booking->user->name ?? '-' }}</td>
                                <td class="py-2 px-4 border-b">{{ $booking->equipment->name ?? '-' }}</td>
                                <td class="py-2 px-4 border-b">{{ $booking->borrow_date }}</td>
                                <td class="py-2 px-4 border-b">{{ $booking->return_date }}</td>
                                <td class="py-2 px-4 border-b text-center">
                                    <a href="{{ route('manage.bookings.review.show', $booking->id) }}" 
                                       class="bg-pink-500 text-white px-3 py-1 rounded hover:bg-pink-600 transition">
                                       ดูรายละเอียด
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-gray-500">
                                    ไม่มีคำขอที่รอพิจารณา
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
