<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QR Scanner - Selecta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mt-3">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-qrcode me-2"></i>
                            QR Scanner - Selecta Ticket
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Scanner Camera</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <div id="reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
                                        <div class="mt-3">
                                            <button id="start-scan" class="btn btn-success me-2">
                                                <i class="fas fa-play me-1"></i>
                                                Start Scanner
                                            </button>
                                            <button id="stop-scan" class="btn btn-danger" disabled>
                                                <i class="fas fa-stop me-1"></i>
                                                Stop Scanner
                                            </button>
                                        </div>
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                Arahkan kamera ke QR Code pada tiket untuk memindai
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Detail Booking</h6>
                                    </div>
                                    <div class="card-body" id="booking-detail">
                                        <div class="text-center text-muted py-5">
                                            <i class="fas fa-qrcode fa-3x mb-3"></i>
                                            <p>Scan QR Code untuk melihat detail booking</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Check-in Confirmation Modal -->
    <div class="modal fade" id="checkinModal" tabindex="-1" aria-labelledby="checkinModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkinModalLabel">Konfirmasi Check-in</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin melakukan check-in untuk booking ini?</p>
                    <div id="checkin-booking-info"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="confirm-checkin">
                        <i class="fas fa-check me-1"></i>
                        Konfirmasi Check-in
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let html5QrcodeScanner;
        let currentQRCode = null;

        $(document).ready(function() {
            $('#start-scan').click(function() {
                startScanner();
            });

            $('#stop-scan').click(function() {
                stopScanner();
            });

            $('#confirm-checkin').click(function() {
                if (currentQRCode) {
                    performCheckin(currentQRCode);
                }
            });
        });

        function startScanner() {
            html5QrcodeScanner = new Html5Qrcode("reader");
            
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    const cameraId = devices[0].id;
                    
                    html5QrcodeScanner.start(
                        cameraId,
                        {
                            fps: 10,
                            qrbox: { width: 250, height: 250 }
                        },
                        (decodedText, decodedResult) => {
                            onScanSuccess(decodedText);
                        },
                        (errorMessage) => {
                            // Handle scan error
                        }
                    ).catch(err => {
                        console.error('Error starting scanner:', err);
                        alert('Gagal memulai scanner. Pastikan kamera tersedia.');
                    });

                    $('#start-scan').prop('disabled', true);
                    $('#stop-scan').prop('disabled', false);
                }
            }).catch(err => {
                console.error('Error getting cameras:', err);
                alert('Tidak dapat mengakses kamera.');
            });
        }

        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    $('#start-scan').prop('disabled', false);
                    $('#stop-scan').prop('disabled', true);
                }).catch(err => {
                    console.error('Error stopping scanner:', err);
                });
            }
        }

        function onScanSuccess(decodedText) {
            // Extract QR code from URL
            const url = new URL(decodedText);
            const pathParts = url.pathname.split('/');
            const qrCode = pathParts[pathParts.length - 1];
            
            currentQRCode = qrCode;
            
            // Fetch booking details
            fetch(`/qr/scan/${qrCode}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        $('#booking-detail').html(data.html);
                    } else {
                        $('#booking-detail').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                ${data.message}
                            </div>
                        `);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    $('#booking-detail').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Terjadi kesalahan saat memuat data booking.
                        </div>
                    `);
                });
        }

        function showCheckinModal(qrCode, bookingCode, bookerName) {
            currentQRCode = qrCode;
            $('#checkin-booking-info').html(`
                <strong>Kode Booking:</strong> ${bookingCode}<br>
                <strong>Nama Pemesan:</strong> ${bookerName}
            `);
            $('#checkinModal').modal('show');
        }

        function performCheckin(qrCode) {
            $.ajax({
                url: `/qr/checkin/${qrCode}`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#checkinModal').modal('hide');
                    
                    if (response.success) {
                        // Refresh booking detail
                        fetch(`/qr/scan/${qrCode}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    $('#booking-detail').html(data.html);
                                }
                            });
                        
                        // Show success message
                        $('#booking-detail').prepend(`
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i>
                                ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                    } else {
                        $('#booking-detail').prepend(`
                            <div class="alert alert-warning alert-dismissible fade show">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                    }
                },
                error: function(xhr) {
                    $('#checkinModal').modal('hide');
                    const response = xhr.responseJSON;
                    $('#booking-detail').prepend(`
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ${response?.message || 'Terjadi kesalahan saat check-in.'}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                }
            });
        }
    </script>
</body>
</html>
