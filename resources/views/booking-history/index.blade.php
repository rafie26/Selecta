@extends('layouts.app')

@section('title', 'Riwayat Pemesanan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <button onclick="goBack()" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                <span class="font-medium">Kembali</span>
            </button>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Riwayat Pemesanan</h1>
            <p class="text-gray-600">Lihat semua riwayat pemesanan tiket dan hotel Anda</p>
        </div>

        <!-- Filter Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('booking-history.index', ['filter' => 'all']) }}" 
                       class="py-2 px-1 border-b-2 font-medium text-sm {{ $filter === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Semua Pemesanan
                    </a>
                    <a href="{{ route('booking-history.index', ['filter' => 'ticket']) }}" 
                       class="py-2 px-1 border-b-2 font-medium text-sm {{ $filter === 'ticket' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Tiket Wisata
                    </a>
                    <a href="{{ route('booking-history.index', ['filter' => 'hotel']) }}" 
                       class="py-2 px-1 border-b-2 font-medium text-sm {{ $filter === 'hotel' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Hotel
                    </a>
                </nav>
            </div>
        </div>

        <!-- Booking Cards -->
        @if($bookings->count() > 0)
            <div class="space-y-6">
                @foreach($bookings as $booking)
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                <!-- Booking Info -->
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <div class="flex items-center">
                                            @if($booking->booking_type === 'hotel')
                                                <i class="fas fa-hotel text-blue-500 mr-2"></i>
                                                <span class="text-sm font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded">Hotel</span>
                                            @else
                                                <i class="fas fa-ticket-alt text-green-500 mr-2"></i>
                                                <span class="text-sm font-medium text-green-600 bg-green-100 px-2 py-1 rounded">Tiket</span>
                                            @endif
                                        </div>
                                        
                                        <!-- Status Badge -->
                                        <div class="ml-3">
                                            @if($booking->payment_status == 'paid')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i> Lunas
                                                </span>
                                            @elseif($booking->payment_status == 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i> Menunggu Pembayaran
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i> Gagal
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $booking->booking_code }}</h3>
                                    
                                    <!-- Booking Details -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm text-gray-600">
                                        <div>
                                            <span class="font-medium">Tanggal Pesan:</span><br>
                                            {{ $booking->created_at->format('d M Y, H:i') }}
                                        </div>
                                        
                                        @if($booking->booking_type === 'hotel')
                                            <div>
                                                <span class="font-medium">Check-in:</span><br>
                                                {{ $booking->visit_date->format('d M Y') }}
                                                @if($booking->check_in_time)
                                                    <br><span class="text-blue-600 font-medium">{{ $booking->check_in_time }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <span class="font-medium">Check-out:</span><br>
                                                {{ $booking->check_out_date->format('d M Y') }}
                                                @if($booking->check_out_time)
                                                    <br><span class="text-blue-600 font-medium">{{ $booking->check_out_time }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <span class="font-medium">Durasi:</span><br>
                                                {{ $booking->nights }} malam
                                            </div>
                                        @else
                                            <div>
                                                <span class="font-medium">Tanggal Kunjungan:</span><br>
                                                {{ $booking->visit_date->format('d M Y') }}
                                            </div>
                                            @if($booking->check_in_time)
                                                <div>
                                                    <span class="font-medium">Check-in:</span><br>
                                                    <span class="text-green-600 font-medium">{{ $booking->check_in_time }}</span>
                                                </div>
                                            @endif
                                            @if($booking->check_out_time)
                                                <div>
                                                    <span class="font-medium">Check-out:</span><br>
                                                    <span class="text-green-600 font-medium">{{ $booking->check_out_time }}</span>
                                                </div>
                                            @endif
                                        @endif
                                    </div>

                                    <!-- Package/Room Details -->
                                    @if($booking->booking_type !== 'hotel' && $booking->bookingDetails->count() > 0)
                                        <div class="mt-3">
                                            <span class="text-sm font-medium text-gray-700">Paket:</span>
                                            <div class="flex flex-wrap gap-2 mt-1">
                                                @foreach($booking->bookingDetails as $detail)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ $detail->package->name ?? 'Paket Tiket' }} ({{ $detail->quantity }}x)
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Price & Actions -->
                                <div class="mt-4 lg:mt-0 lg:ml-6 flex flex-col items-end">
                                    <div class="text-right mb-3">
                                        <div class="text-2xl font-bold text-gray-900">
                                            Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                        </div>
                                        @if($booking->payment_status === 'paid' && $booking->paid_at)
                                            <div class="text-sm text-gray-500">
                                                Dibayar: {{ $booking->paid_at->format('d M Y, H:i') }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex space-x-2">
                                        <a href="{{ route('booking-history.show', $booking->id) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </a>
                                        
                                        @if($booking->payment_status === 'pending')
                                            <!-- Pay Button for Pending Bookings -->
                                            <button data-booking-id="{{ $booking->id }}" data-booking-type="{{ $booking->booking_type }}" 
                                                    onclick="payBooking(this.dataset.bookingId, this.dataset.bookingType)" 
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <i class="fas fa-credit-card mr-1"></i> Bayar Sekarang
                                            </button>
                                        @elseif($booking->payment_status === 'paid')
                                            <!-- Check-in/out Time Buttons -->
                                            @if(!$booking->check_in_time)
                                                <button data-booking-id="{{ $booking->id }}" 
                                                        onclick="setCheckInTime(this.dataset.bookingId)" 
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    <i class="fas fa-sign-in-alt mr-1"></i> Set Check-in
                                                </button>
                                            @endif
                                            
                                            @if($booking->check_in_time && !$booking->check_out_time)
                                                <button data-booking-id="{{ $booking->id }}" 
                                                        onclick="setCheckOutTime(this.dataset.bookingId)" 
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <i class="fas fa-sign-out-alt mr-1"></i> Set Check-out
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <i class="fas fa-history text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Riwayat Pemesanan</h3>
                <p class="text-gray-500 mb-6">
                    @if($filter === 'ticket')
                        Anda belum memiliki riwayat pemesanan tiket.
                    @elseif($filter === 'hotel')
                        Anda belum memiliki riwayat pemesanan hotel.
                    @else
                        Anda belum memiliki riwayat pemesanan apapun.
                    @endif
                </p>
                <div class="space-x-4">
                    <a href="{{ route('ticket.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-ticket-alt mr-2"></i> Pesan Tiket
                    </a>
                    <a href="{{ route('hotels.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-hotel mr-2"></i> Pesan Hotel
                    </a>
                </div>
            </div>
        @endif
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

 <script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
 <script>
 let currentBookingId = null;

 function payBooking(bookingId, bookingType) {
     // Fallback booking type for legacy data
     if (!bookingType) {
         bookingType = 'ticket';
     }

     if (typeof window.snap === 'undefined') {
         alert('Layanan pembayaran tidak tersedia saat ini. Silakan coba beberapa saat lagi.');
         return;
     }

     const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

     fetch('/payment', {
         method: 'POST',
         headers: {
             'Content-Type': 'application/json',
             'X-CSRF-TOKEN': csrfToken,
             'Accept': 'application/json'
         },
         body: JSON.stringify({
             booking_id: bookingId,
             booking_type: bookingType
         })
     })
     .then(response => response.json().then(data => ({ ok: response.ok, status: response.status, data })))
     .then(({ ok, data }) => {
         if (!ok) {
             const message = data && data.message ? data.message : 'Terjadi kesalahan saat memproses pembayaran.';
             alert(message);
             return;
         }

         if (!data.snap_token) {
             alert('Token pembayaran tidak ditemukan. Silakan coba lagi.');
             return;
         }

         window.snap.pay(data.snap_token, {
             onSuccess: function () {
                 alert('Pembayaran berhasil!');
                 setTimeout(function () {
                     window.location.href = `/payment/success/${bookingId}`;
                 }, 1000);
             },
             onPending: function () {
                 alert('Pembayaran masih pending. Silakan selesaikan pembayaran Anda.');
             },
             onError: function () {
                 alert('Pembayaran gagal. Silakan coba lagi.');
             },
             onClose: function () {
                 alert('Anda menutup jendela pembayaran sebelum menyelesaikan transaksi.');
             }
         });
     })
     .catch(error => {
         console.error('Payment error:', error);
         alert('Terjadi kesalahan saat memproses pembayaran.');
     });
 }


 function setCheckInTime(bookingId) {
    currentBookingId = bookingId;
    document.getElementById('checkInModal').classList.remove('hidden');
    // Set current time as default
    const now = new Date();
    const timeString = now.toTimeString().slice(0, 5);
    document.getElementById('checkInTime').value = timeString;
}

function setCheckOutTime(bookingId) {
    currentBookingId = bookingId;
    document.getElementById('checkOutModal').classList.remove('hidden');
    // Set current time as default
    const now = new Date();
    const timeString = now.toTimeString().slice(0, 5);
    document.getElementById('checkOutTime').value = timeString;
}

function closeCheckInModal() {
    document.getElementById('checkInModal').classList.add('hidden');
    currentBookingId = null;
}

function closeCheckOutModal() {
    document.getElementById('checkOutModal').classList.add('hidden');
    currentBookingId = null;
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
        // Fallback to home page if no history
        window.location.href = '/';
    }
}
</script>
@endsection
