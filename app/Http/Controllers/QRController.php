<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class QRController extends Controller
{
    public function generateQR(Booking $booking)
    {
        if (!$booking->qr_code) {
            $booking->qr_code = $booking->generateQRCode();
            $booking->save();
        }

        $qrCode = QrCode::size(300)
            ->format('png')
            ->generate(route('qr.scan', $booking->qr_code));

        return response($qrCode)
            ->header('Content-Type', 'image/png');
    }

    public function scanPage()
    {
        return view('qr.scanner');
    }

    public function scan($qrCode)
    {
        $booking = Booking::where('qr_code', $qrCode)
            ->with(['user', 'visitors', 'bookingDetails.package'])
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid atau booking tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'booking' => $booking,
            'html' => view('qr.booking-detail', compact('booking'))->render()
        ]);
    }

    public function checkIn(Request $request, $qrCode)
    {
        $booking = Booking::where('qr_code', $qrCode)->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan.'
            ], 404);
        }

        if ($booking->payment_status !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Booking belum dibayar.'
            ], 400);
        }

        if ($booking->check_in_status === 'checked_in') {
            return response()->json([
                'success' => false,
                'message' => 'Booking sudah di-check in sebelumnya pada ' . $booking->checked_in_at->format('d M Y H:i')
            ], 400);
        }

        $booking->update([
            'check_in_status' => 'checked_in',
            'checked_in_at' => Carbon::now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil!',
            'booking' => $booking
        ]);
    }
}
