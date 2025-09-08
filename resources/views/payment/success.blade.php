<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Selecta</title>
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Barcode Library -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .success-container {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 500px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            position: relative;
        }

        .success-header {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            padding: 40px 32px;
            color: white;
            text-align: center;
            position: relative;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.15);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 36px;
        }

        .success-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .success-subtitle {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 400;
        }

        .ticket-body {
            padding: 40px 32px;
            background: white;
        }

        .barcode-section {
            text-align: center;
            margin-bottom: 32px;
        }

        .barcode-container {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 16px;
        }

        .barcode {
            margin: 0 auto;
        }

        .ticket-code {
            font-family: 'SF Mono', 'Monaco', 'Cascadia Code', monospace;
            font-size: 18px;
            font-weight: 600;
            color: #1e40af;
            letter-spacing: 1px;
            background: #eff6ff;
            padding: 12px 24px;
            border-radius: 12px;
            display: inline-block;
            margin-top: 16px;
            border: 1px solid #dbeafe;
        }

        .ticket-details {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 28px;
            margin: 28px 0;
        }

        .detail-grid {
            display: grid;
            gap: 20px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .detail-row:last-child {
            border-bottom: none;
            font-weight: 700;
            color: #1e40af;
            font-size: 18px;
            background: #eff6ff;
            margin: 16px -20px -20px -20px;
            padding: 20px;
            border-radius: 0 0 12px 12px;
        }

        .detail-label {
            color: #64748b;
            font-weight: 500;
            font-size: 15px;
        }

        .detail-value {
            color: #0f172a;
            font-weight: 600;
            font-size: 15px;
        }

        .visitor-list {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            margin: 24px 0;
        }

        .visitor-list h4 {
            color: #1e40af;
            margin-bottom: 20px;
            font-size: 17px;
            font-weight: 600;
        }

        .visitor-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .visitor-item:last-child {
            border-bottom: none;
        }

        .visitor-name {
            font-weight: 600;
            color: #0f172a;
        }

        .visitor-info {
            font-size: 14px;
            color: #64748b;
            background: #e0e7ff;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 32px;
        }

        .btn {
            padding: 16px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
            border: 2px solid #3b82f6;
        }

        .btn-primary:hover {
            background: #2563eb;
            border-color: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-secondary {
            background: white;
            color: #3b82f6;
            border: 2px solid #3b82f6;
        }

        .btn-secondary:hover {
            background: #eff6ff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
        }

        .instructions {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #bfdbfe;
            border-radius: 16px;
            padding: 24px;
            margin: 24px 0;
            text-align: center;
        }

        .instructions h4 {
            color: #1e40af;
            margin-bottom: 12px;
            font-size: 16px;
            font-weight: 600;
        }

        .instructions p {
            color: #1e40af;
            font-size: 14px;
            line-height: 1.6;
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .success-container {
                max-width: 420px;
                margin: 20px;
            }

            .success-header {
                padding: 32px 24px;
            }

            .ticket-body {
                padding: 32px 24px;
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }

            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }

            .detail-row:last-child {
                flex-direction: row;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-header">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <div class="success-title">Pembayaran Berhasil!</div>
            <div class="success-subtitle">Tiket Anda sudah siap digunakan</div>
        </div>
        
        <div class="ticket-body">
            <div class="barcode-section">
                <div class="barcode-container">
                    <svg id="barcode" class="barcode"></svg>
                    <div class="ticket-code">{{ $booking->booking_code }}</div>
                </div>
            </div>

            <div class="ticket-details">
                <div class="detail-grid">
                    <div class="detail-row">
                        <span class="detail-label">Kode Booking:</span>
                        <span class="detail-value">{{ $booking->booking_code }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Tanggal Kunjungan:</span>
                        <span class="detail-value">{{ $booking->visit_date->format('d M Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Waktu:</span>
                        <span class="detail-value">08:00 - 17:00 WIB</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Pemesan:</span>
                        <span class="detail-value">{{ $booking->booker_name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">{{ $booking->booker_email }}</span>
                    </div>
                    @foreach($booking->bookingDetails as $detail)
                    <div class="detail-row">
                        <span class="detail-label">{{ $detail->package->name }}:</span>
                        <span class="detail-value">{{ $detail->quantity }}x</span>
                    </div>
                    @endforeach
                    <div class="detail-row">
                        <span class="detail-label">Total Bayar:</span>
                        <span class="detail-value">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            @if($booking->visitors->count() > 0)
            <div class="visitor-list">
                <h4><i class="fas fa-users"></i> Daftar Pengunjung</h4>
                @foreach($booking->visitors as $visitor)
                    <div class="visitor-item">
                    <div>
                        <div class="visitor-name">{{ $visitor->name }}</div>
                    </div>
                    <div class="visitor-info">{{ $visitor->age_category }}</div>
                </div>
                @endforeach
            </div>
            @endif

            <div class="instructions">
                <h4><i class="fas fa-info-circle"></i> Petunjuk Penggunaan</h4>
                <p>Tunjukkan barcode ini di pintu masuk Selecta. Screenshot atau simpan halaman ini untuk akses mudah saat berkunjung.</p>
            </div>

            <div class="action-buttons">
                <button class="btn btn-primary" onclick="downloadTicket()">
                    <i class="fas fa-download"></i>
                    Simpan Tiket
                </button>
                <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                    <i class="fas fa-home"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

<script>
    // Generate barcode
    document.addEventListener('DOMContentLoaded', function() {
        JsBarcode("#barcode", "{{ $booking->booking_code }}", {
            format: "CODE128",
            width: 2,
            height: 80,
            displayValue: false,
            background: "#f8fafc",
            lineColor: "#1e40af"
        });
    });

    function downloadTicket() {
        // Create a printable version
        window.print();
    }
</script>

@if(isset($booking) && $booking->payment_status === 'pending')
<script>
    // Auto-refresh booking status if payment is still pending
    setTimeout(function() {
        location.reload();
    }, 5000);
</script>
@endif
</script>
</body>
</html>