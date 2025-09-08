<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Pemesan</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Akun User:</strong>
                    @if($booking->user)
                        <div class="d-flex align-items-center mt-1">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px; font-size: 12px;">
                                {{ $booking->user->initials }}
                            </div>
                            <div>
                                <div class="fw-medium">{{ $booking->user->name }}</div>
                                <small class="text-muted">{{ $booking->user->email }}</small>
                            </div>
                        </div>
                    @else
                        <span class="text-muted">Booking tanpa akun (guest)</span>
                    @endif
                </div>
                
                <div class="mb-3">
                    <strong>Nama Pemesan:</strong>
                    <div>{{ $booking->booker_name }}</div>
                </div>
                
                <div class="mb-3">
                    <strong>Email:</strong>
                    <div>{{ $booking->booker_email }}</div>
                </div>
                
                <div class="mb-3">
                    <strong>Telepon:</strong>
                    <div>{{ $booking->booker_phone ?: '-' }}</div>
                </div>
                
                <div class="mb-3">
                    <strong>Kode Booking:</strong>
                    <div class="fw-medium text-primary">{{ $booking->booking_code }}</div>
                </div>
                
                <div class="mb-3">
                    <strong>Tanggal Kunjungan:</strong>
                    <div>{{ $booking->visit_date ? \Carbon\Carbon::parse($booking->visit_date)->format('d M Y') : '-' }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-users me-2"></i>Data Pengunjung</h6>
            </div>
            <div class="card-body">
                @if($booking->visitors->count() > 0)
                    <div class="mb-3">
                        <strong>Total Pengunjung: {{ $booking->visitors->count() }} orang</strong>
                    </div>
                    
                    @foreach($booking->visitors as $index => $visitor)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px; font-size: 12px;">
                                    {{ $index + 1 }}
                                </div>
                                <strong>{{ $visitor->name }}</strong>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Jenis Kelamin:</small>
                                    <div>
                                        @if($visitor->gender == 'L')
                                            <i class="fas fa-mars text-primary me-1"></i>Laki-laki
                                        @else
                                            <i class="fas fa-venus text-danger me-1"></i>Perempuan
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Umur:</small>
                                    <div>{{ $visitor->age }} tahun</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-user-slash fa-2x mb-2"></i>
                        <p class="mb-0">Tidak ada data pengunjung</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Detail Ticket</h6>
            </div>
            <div class="card-body">
                @if($booking->bookingDetails->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Ticket</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->bookingDetails as $detail)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $detail->package->name }}</div>
                                            @if($detail->package->description)
                                                <small class="text-muted">{{ $detail->package->description }}</small>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($detail->unit_price) }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td class="fw-medium">Rp {{ number_format($detail->subtotal) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-primary">
                                    <th colspan="3">Total</th>
                                    <th>Rp {{ number_format($booking->total_amount) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-ticket-alt fa-2x mb-2"></i>
                        <p class="mb-0">Tidak ada detail ticket</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-credit-card me-2"></i>Informasi Pembayaran</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Status Pembayaran:</strong>
                        <div>
                            <span class="badge bg-{{ $booking->payment_status == 'paid' ? 'success' : ($booking->payment_status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <strong>Status Check-in:</strong>
                        <div>
                            <span class="badge bg-{{ $booking->check_in_status == 'checked_in' ? 'success' : 'warning' }}">
                                {{ $booking->check_in_status == 'checked_in' ? 'Sudah Check-in' : 'Belum Check-in' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <strong>Metode Pembayaran:</strong>
                        <div>{{ $booking->payment_method ?: '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <strong>Tanggal Dibayar:</strong>
                        <div>{{ $booking->paid_at ? $booking->paid_at->format('d M Y H:i') : '-' }}</div>
                    </div>
                </div>
                
                @if($booking->check_in_status == 'checked_in')
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <strong>Tanggal Check-in:</strong>
                            <div>{{ $booking->checked_in_at ? $booking->checked_in_at->format('d M Y H:i') : '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>QR Code:</strong>
                            <div>
                                @if($booking->qr_code && $booking->payment_status == 'paid')
                                    <img src="{{ route('qr.generate', $booking) }}" alt="QR Code" class="img-fluid" style="max-width: 100px;">
                                @else
                                    <span class="text-muted">Belum tersedia</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($booking->midtrans_order_id)
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Midtrans Order ID:</strong>
                            <div class="small">{{ $booking->midtrans_order_id }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Midtrans Transaction ID:</strong>
                            <div class="small">{{ $booking->midtrans_transaction_id ?: '-' }}</div>
                        </div>
                    </div>
                @endif
                
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Dibuat:</strong>
                        <div>{{ $booking->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Terakhir Update:</strong>
                        <div>{{ $booking->updated_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
