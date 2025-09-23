<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\BookingDetail;
use App\Models\Visitor;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Picqer\Barcode\BarcodeGeneratorPNG;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
        
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function pay(Request $request)
    {
        // Check if this is a hotel booking payment
        if ($request->has('booking_id') && $request->has('booking_type') && $request->booking_type === 'hotel') {
            return $this->payHotelBooking($request);
        }

        // Check if this is a ticket booking payment
        if ($request->has('booking_id') && $request->has('booking_type') && $request->booking_type === 'ticket') {
            return $this->payTicketBooking($request);
        }

        $request->validate([
            'visit_date' => 'required|date|after:today',
            'packages' => 'required|array',
            'visitors' => 'array',
        ]);

        $user = Auth::user();
        
        // Use MidtransService to get latest prices and calculate total
        $packageDetails = $this->midtransService->preparePackageDetails($request->packages);
        $totalAmount = $this->midtransService->calculateTotalAmount($request->packages);
        
        // Validate at least one package selected
        if (empty($packageDetails)) {
            return back()->withErrors(['packages' => 'Pilih minimal satu paket wisata.']);
        }

        DB::beginTransaction();
        try {
            // Generate booking code first
            $bookingCode = 'SLT-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            
            // Create booking with the generated code
            $booking = Booking::create([
                'booking_code' => $bookingCode,
                'user_id' => $user->id,
                'booker_name' => $user->name,
                'booker_email' => $user->email,
                'booker_phone' => $user->phone ?? '',
                'visit_date' => $request->visit_date,
                'total_amount' => $totalAmount,
                'payment_status' => 'pending',
                'payment_method' => null,
            ]);

            // Create booking details for each package
            foreach ($packageDetails as $detail) {
                BookingDetail::create([
                    'booking_id' => $booking->id,
                    'package_id' => $detail['package']->id,
                    'quantity' => $detail['quantity'],
                    'unit_price' => $detail['package']->price,
                    'subtotal' => $detail['subtotal'],
                ]);
            }

            // Create visitors (filter out empty entries)
            if ($request->visitors) {
                foreach ($request->visitors as $visitorData) {
                    if (!empty($visitorData['name']) && !empty($visitorData['age_category'])) {
                        Visitor::create([
                            'booking_id' => $booking->id,
                            'name' => $visitorData['name'],
                            'age_category' => $visitorData['age_category'],
                        ]);
                    }
                }
            }

            // Prepare Midtrans transaction details
            $orderId = 'SELECTA-' . $booking->booking_code . '-' . time();
            $booking->midtrans_order_id = $orderId;
            $booking->save();

            // Prepare item details for Midtrans using service
            $itemDetails = $this->midtransService->prepareItemDetails($packageDetails);

            // Prepare transaction data
            $transactionDetails = [
                'order_id' => $orderId,
                'gross_amount' => $totalAmount,
            ];

            $customerDetails = [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
            ];

            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'callbacks' => [
                    'finish' => route('payment.success', $booking->id),
                ]
            ];

            // Get Snap Token
            $snapToken = Snap::getSnapToken($params);

            DB::commit();

            return response()->json([
                'snap_token' => $snapToken,
                'booking_id' => $booking->id
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            // Log the detailed error
            Log::error('Payment processing failed', [
                'error_message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return JSON error for AJAX requests
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Terjadi kesalahan internal. Silakan coba lagi nanti.'
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    public function payHotelBooking(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $user = Auth::user();
        $booking = Booking::where('booking_type', 'hotel')->findOrFail($request->booking_id);

        // Verify booking belongs to user
        if ($booking->user_id !== $user->id) {
            return response()->json([
                'error' => true,
                'message' => 'Booking tidak ditemukan atau tidak memiliki akses.'
            ], 403);
        }

        // Check if booking is already paid
        if ($booking->payment_status === 'paid') {
            return response()->json([
                'error' => true,
                'message' => 'Booking sudah dibayar.'
            ], 400);
        }

        try {
            // Prepare Midtrans transaction details
            $orderId = 'HOTEL-' . $booking->booking_code . '-' . time();
            $booking->midtrans_order_id = $orderId;
            $booking->save();

            // Decode hotel rooms data
            $hotelRoomsData = json_decode($booking->hotel_rooms_data, true) ?? [];
            
            // Prepare item details for hotel booking with breakdown
            $itemDetails = [];
            
            // Add subtotal as main item
            $itemDetails[] = [
                'id' => 'hotel-subtotal',
                'price' => $booking->subtotal,
                'quantity' => 1,
                'name' => 'Hotel Room Booking - ' . $booking->nights . ' malam',
                'category' => 'Hotel Room'
            ];
            
            // Add tax as separate item
            if ($booking->tax_amount > 0) {
                $itemDetails[] = [
                    'id' => 'hotel-tax',
                    'price' => $booking->tax_amount,
                    'quantity' => 1,
                    'name' => 'Pajak (11%)',
                    'category' => 'Tax'
                ];
            }
            
            // Add service charge as separate item
            if ($booking->service_amount > 0) {
                $itemDetails[] = [
                    'id' => 'hotel-service',
                    'price' => $booking->service_amount,
                    'quantity' => 1,
                    'name' => 'Biaya Layanan (5%)',
                    'category' => 'Service'
                ];
            }

            // Prepare transaction data with final total amount (including tax and service)
            $transactionDetails = [
                'order_id' => $orderId,
                'gross_amount' => $booking->total_amount, // This already includes tax and service
            ];

            $customerDetails = [
                'first_name' => $booking->booker_name,
                'email' => $booking->booker_email,
                'phone' => $booking->booker_phone ?? '',
            ];

            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'callbacks' => [
                    'finish' => route('payment.success', $booking->id),
                ]
            ];

            // Get Snap Token
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'snap_token' => $snapToken,
                'booking_id' => $booking->id
            ]);

        } catch (\Exception $e) {
            Log::error('Hotel payment processing failed', [
                'booking_id' => $booking->id,
                'error_message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan internal. Silakan coba lagi nanti.'
            ], 500);
        }
    }

    public function notificationHandler(Request $request)
    {
        $notification = new Notification();

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;
        $fraudStatus = $notification->fraud_status;

        $booking = Booking::where('midtrans_order_id', $orderId)->first();

        if (!$booking) {
            return response()->json(['status' => 'error', 'message' => 'Booking not found'], 404);
        }

        // Update booking based on transaction status
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $booking->payment_status = 'pending';
            } else if ($fraudStatus == 'accept') {
                $booking->payment_status = 'paid';
                $booking->paid_at = now();
                $booking->payment_method = $notification->payment_type;
                
                // Generate QR code when payment is successful
                if (!$booking->qr_code) {
                    $booking->qr_code = $booking->generateQRCode();
                }
                
                $this->generateBarcode($booking);
            }
        } else if ($transactionStatus == 'settlement') {
            $booking->payment_status = 'paid';
            $booking->paid_at = now();
            $booking->payment_method = $notification->payment_type;
            
            // Generate QR code when payment is successful
            if (!$booking->qr_code) {
                $booking->qr_code = $booking->generateQRCode();
            }
            
            $this->generateBarcode($booking);
        } else if ($transactionStatus == 'pending') {
            $booking->payment_status = 'pending';
        } else if ($transactionStatus == 'deny') {
            $booking->payment_status = 'failed';
        } else if ($transactionStatus == 'expire') {
            $booking->payment_status = 'expired';
        } else if ($transactionStatus == 'cancel') {
            $booking->payment_status = 'failed';
        }

        $booking->midtrans_transaction_id = $notification->transaction_id;
        $booking->midtrans_response = json_encode($notification->getResponse());
        $booking->save();

        return response()->json(['status' => 'success']);
    }

    public function payTicketBooking(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $user = Auth::user();
        $booking = Booking::with(['bookingDetails.package'])->findOrFail($request->booking_id);

        // Verify booking belongs to user
        if ($booking->user_id !== $user->id) {
            return response()->json([
                'error' => true,
                'message' => 'Booking tidak ditemukan atau tidak memiliki akses.'
            ], 403);
        }

        // Check if booking is already paid
        if ($booking->payment_status === 'paid') {
            return response()->json([
                'error' => true,
                'message' => 'Booking sudah dibayar.'
            ], 400);
        }

        try {
            // Prepare Midtrans transaction details
            $orderId = 'TICKET-' . $booking->booking_code . '-' . time();
            $booking->midtrans_order_id = $orderId;
            $booking->save();

            // Prepare item details for ticket booking
            $itemDetails = [];
            foreach ($booking->bookingDetails as $detail) {
                $itemDetails[] = [
                    'id' => 'ticket-' . $detail->package_id,
                    'price' => $detail->unit_price,
                    'quantity' => $detail->quantity,
                    'name' => $detail->package->name,
                    'category' => 'Ticket'
                ];
            }

            // Prepare transaction data
            $transactionDetails = [
                'order_id' => $orderId,
                'gross_amount' => $booking->total_amount,
            ];

            $customerDetails = [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
            ];

            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'callbacks' => [
                    'finish' => route('payment.success', $booking->id),
                ]
            ];

            // Get Snap Token
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'snap_token' => $snapToken,
                'booking_id' => $booking->id
            ]);

        } catch (\Exception $e) {
            Log::error('Ticket payment processing failed', [
                'booking_id' => $booking->id,
                'error_message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan internal. Silakan coba lagi nanti.'
            ], 500);
        }
    }

    public function success($bookingId)
    {
        $booking = Booking::with(['bookingDetails.package', 'visitors', 'user'])
                          ->where('id', $bookingId)
                          ->where('user_id', Auth::id())
                          ->first();

        if (!$booking) {
            return redirect()->route('tickets.index')->with('error', 'Booking tidak ditemukan');
        }

        // SELALU update status ke paid ketika user sampai di halaman success
        // Ini berarti pembayaran berhasil dilakukan
        if ($booking->payment_status !== 'paid') {
            $booking->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
                'payment_method' => 'midtrans_payment'
            ]);
            
            // Generate QR code when payment is successful
            if (!$booking->qr_code) {
                $booking->qr_code = $booking->generateQRCode();
                $booking->save();
            }
            
            $this->generateBarcode($booking);
        }

        return view('payment.success', compact('booking'));
    }

    public function manualUpdateStatus(Request $request, $bookingId)
    {
        $booking = Booking::where('id', $bookingId)
                          ->where('user_id', Auth::id())
                          ->first();

        if (!$booking) {
            return response()->json(['error' => 'Booking tidak ditemukan'], 404);
        }

        if ($booking->payment_status !== 'pending') {
            return response()->json(['error' => 'Booking sudah diproses'], 400);
        }

        $booking->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
            'payment_method' => 'manual_update'
        ]);

        // Generate QR code when payment is successful
        if (!$booking->qr_code) {
            $booking->qr_code = $booking->generateQRCode();
            $booking->save();
        }
        
        $this->generateBarcode($booking);

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diupdate!'
        ]);
    }

    private function generateBarcode($booking)
    {
        try {
            $generator = new BarcodeGeneratorPNG();
            $barcode = $generator->getBarcode($booking->booking_code, $generator::TYPE_CODE_128);
            
            // Save barcode to storage
            $filename = 'barcodes/' . $booking->booking_code . '.png';
            $path = storage_path('app/public/' . $filename);
            
            // Create directory if it doesn't exist
            $directory = dirname($path);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            file_put_contents($path, $barcode);
            
            // Update booking with barcode path
            $booking->barcode_path = $filename;
            $booking->save();
            
        } catch (\Exception $e) {
            Log::error('Barcode generation failed', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'error' => $e->getMessage()
            ]);
        }
    }
}
