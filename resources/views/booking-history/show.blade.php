@extends('layouts.app')

@section('title', 'Detail Riwayat Pemesanan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <button onclick="goBack()" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200 mr-4">
                <i class="fas fa-arrow-left mr-2"></i>
                <span class="font-medium">Kembali</span>
            </button>
            <a href="{{ route('booking-history.index') }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors duration-200">
                <i class="fas fa-list mr-2"></i> Riwayat Pemesanan
            </a>
        </div>

        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $booking->booking_code }}</h1>
                        <div class="flex items-center mt-2">
                            @if($booking->booking_type === 'hotel')
                                <i class="fas fa-hotel text-blue-500 mr-2"></i>
                                <span class="text-sm font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded">Hotel</span>
                            @else
                                <i class="fas fa-ticket-alt text-green-500 mr-2"></i>
                                <span class="text-sm font-medium text-green-600 bg-green-100 px-2 py-1 rounded">Tiket</span>
                            @endif
                            
                            <div class="ml-3">
                                @if($booking->payment_status == 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> Gagal
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-gray-900">
                            Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                        </div>
                        @if($booking->payment_status === 'paid' && $booking->paid_at)
                            <div class="text-sm text-gray-500">
                                Dibayar: {{ $booking->paid_at->format('d M Y, H:i') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pemesanan</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Tanggal Pemesanan:</span>
                                <p class="text-gray-900">{{ $booking->created_at->format('d M Y, H:i') }} WIB</p>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Nama Pemesan:</span>
                                <p class="text-gray-900">{{ $booking->booker_name }}</p>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Email:</span>
                                <p class="text-gray-900">{{ $booking->booker_email }}</p>
                            </div>
                            
                            @if($booking->booker_phone)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Telepon:</span>
                                    <p class="text-gray-900">{{ $booking->booker_phone }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            @if($booking->booking_type === 'hotel')
                                Informasi Hotel
                            @else
                                Informasi Kunjungan
                            @endif
                        </h3>
                        
                        <div class="space-y-3">
                            @if($booking->booking_type === 'hotel')
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Check-in:</span>
                                    <p class="text-gray-900">{{ $booking->visit_date->format('d M Y') }}</p>
                                    @if($booking->check_in_time)
                                        <p class="text-blue-600 font-medium">Waktu: {{ $booking->check_in_time }}</p>
                                    @endif
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Check-out:</span>
                                    <p class="text-gray-900">{{ $booking->check_out_date->format('d M Y') }}</p>
                                    @if($booking->check_out_time)
                                        <p class="text-blue-600 font-medium">Waktu: {{ $booking->check_out_time }}</p>
                                    @endif
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Durasi:</span>
                                    <p class="text-gray-900">{{ $booking->nights }} malam</p>
                                </div>
                                
                                @if($booking->total_adults || $booking->total_children)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Tamu:</span>
                                        <p class="text-gray-900">
                                            {{ $booking->total_adults }} Dewasa
                                            @if($booking->total_children > 0)
                                                , {{ $booking->total_children }} Anak
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            @else
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Tanggal Kunjungan:</span>
                                    <p class="text-gray-900">{{ $booking->visit_date->format('d M Y') }}</p>
                                </div>
                                
                                @if($booking->check_in_time)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Waktu Check-in:</span>
                                        <p class="text-green-600 font-medium">{{ $booking->check_in_time }}</p>
                                    </div>
                                @endif
                                
                                @if($booking->check_out_time)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Waktu Check-out:</span>
                                        <p class="text-green-600 font-medium">{{ $booking->check_out_time }}</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Package/Room Details -->
                @if($booking->booking_type !== 'hotel' && $booking->bookingDetails->count() > 0)
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Paket Tiket</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="space-y-3">
                                @foreach($booking->bookingDetails as $detail)
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $detail->package->name ?? 'Paket Tiket' }}</h4>
                                            <p class="text-sm text-gray-500">Quantity: {{ $detail->quantity }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium text-gray-900">Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</p>
                                            <p class="text-sm text-gray-500">Subtotal: Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Hotel Room Details -->
                @if($booking->booking_type === 'hotel' && $booking->hotel_rooms_data)
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Kamar Hotel</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            @php
                                $roomsData = is_string($booking->hotel_rooms_data) ? json_decode($booking->hotel_rooms_data, true) : $booking->hotel_rooms_data;
                            @endphp
                            
                            @if($roomsData && is_array($roomsData))
                                <div class="space-y-4">
                                    @foreach($roomsData as $index => $room)
                                        <div class="border-b border-gray-200 pb-3 last:border-b-0">
                                            <h4 class="font-medium text-gray-900">Kamar {{ $index + 1 }}</h4>
                                            <div class="mt-2 grid grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    <span class="text-gray-500">Jumlah Kamar:</span>
                                                    <span class="text-gray-900">{{ $room['quantity'] ?? 1 }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500">Tamu:</span>
                                                    <span class="text-gray-900">
                                                        {{ $room['guestConfig']['adults'] ?? 0 }} Dewasa
                                                        @if(($room['guestConfig']['children'] ?? 0) > 0)
                                                            , {{ $room['guestConfig']['children'] }} Anak
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Price Breakdown for Hotel -->
                @if($booking->booking_type === 'hotel' && ($booking->subtotal || $booking->tax_amount || $booking->service_amount))
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Rincian Harga</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="space-y-2">
                                @if($booking->subtotal)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span class="text-gray-900">Rp {{ number_format($booking->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                
                                @if($booking->tax_amount)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Pajak (11%):</span>
                                        <span class="text-gray-900">Rp {{ number_format($booking->tax_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                
                                @if($booking->service_amount)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Biaya Layanan (5%):</span>
                                        <span class="text-gray-900">Rp {{ number_format($booking->service_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                
                                <div class="border-t border-gray-300 pt-2 mt-3">
                                    <div class="flex justify-between font-semibold text-lg">
                                        <span class="text-gray-900">Total:</span>
                                        <span class="text-gray-900">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Visitors List -->
                @if($booking->visitors && $booking->visitors->count() > 0)
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Pengunjung</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($booking->visitors as $visitor)
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-900">{{ $visitor->name }}</span>
                                        <span class="text-sm text-gray-500 capitalize">{{ $visitor->age_category }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- QR Code & Barcode -->
                @if($booking->payment_status === 'paid')
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tiket Digital</h3>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            @if($booking->qr_code)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-2">QR Code:</p>
                                    <div class="inline-block p-2 bg-white rounded">
                                        <div class="w-32 h-32 bg-gray-200 flex items-center justify-center text-gray-500 text-sm">
                                            QR: {{ $booking->qr_code }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($booking->barcode_path)
                                <div>
                                    <p class="text-sm text-gray-600 mb-2">Barcode:</p>
                                    <img src="{{ asset('storage/' . $booking->barcode_path) }}" 
                                         alt="Barcode" class="mx-auto max-w-xs">
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                @if($booking->payment_status === 'paid')
                    <div class="mt-8 flex justify-center space-x-4">
                        @if(!$booking->check_in_time)
                            <button data-booking-id="{{ $booking->id }}" 
                                    onclick="setCheckInTime(this.dataset.bookingId)" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-sign-in-alt mr-2"></i> Set Waktu Check-in
                            </button>
                        @endif
                        
                        @if($booking->check_in_time && !$booking->check_out_time)
                            <button data-booking-id="{{ $booking->id }}" 
                                    onclick="setCheckOutTime(this.dataset.bookingId)" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-sign-out-alt mr-2"></i> Set Waktu Check-out
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Check-in Time Modal -->
<div id="checkInModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Set Waktu Check-in</h3>
            <form id="checkInForm">
                <div class="mb-4">
                    <label for="checkInTime" class="block text-sm font-medium text-gray-700 mb-2">Waktu Check-in</label>
                    <input type="time" id="checkInTime" name="check_in_time" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCheckInModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Check-out Time Modal -->
<div id="checkOutModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Set Waktu Check-out</h3>
            <form id="checkOutForm">
                <div class="mb-4">
                    <label for="checkOutTime" class="block text-sm font-medium text-gray-700 mb-2">Waktu Check-out</label>
                    <input type="time" id="checkOutTime" name="check_out_time" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCheckOutModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentBookingId = null; // Set dynamically via function parameters

function setCheckInTime(bookingId) {
    document.getElementById('checkInModal').classList.remove('hidden');
    // Set current time as default
    const now = new Date();
    const timeString = now.toTimeString().slice(0, 5);
    document.getElementById('checkInTime').value = timeString;
}

function setCheckOutTime(bookingId) {
    document.getElementById('checkOutModal').classList.remove('hidden');
    // Set current time as default
    const now = new Date();
    const timeString = now.toTimeString().slice(0, 5);
    document.getElementById('checkOutTime').value = timeString;
}

function closeCheckInModal() {
    document.getElementById('checkInModal').classList.add('hidden');
}

function closeCheckOutModal() {
    document.getElementById('checkOutModal').classList.add('hidden');
}

// Handle check-in form submission
document.getElementById('checkInForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`/riwayat-pemesanan/${currentBookingId}/check-in-time`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Terjadi kesalahan. Silakan coba lagi.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
    
    closeCheckInModal();
});

// Handle check-out form submission
document.getElementById('checkOutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`/riwayat-pemesanan/${currentBookingId}/check-out-time`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Terjadi kesalahan. Silakan coba lagi.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
    
    closeCheckOutModal();
});

// Close modals when clicking outside
window.onclick = function(event) {
    const checkInModal = document.getElementById('checkInModal');
    const checkOutModal = document.getElementById('checkOutModal');
    
    if (event.target === checkInModal) {
        closeCheckInModal();
    }
    if (event.target === checkOutModal) {
        closeCheckOutModal();
    }
}

// Back button function
function goBack() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        // Fallback to booking history page
        window.location.href = '{{ route("booking-history.index") }}';
    }
}
</script>
@endsection
