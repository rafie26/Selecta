<?php

namespace App\Services;

use App\Models\Package;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Prepare item details for Midtrans transaction
     * This ensures we always use the latest price from database
     */
    public function prepareItemDetails(array $packageDetails): array
    {
        $itemDetails = [];
        
        foreach ($packageDetails as $detail) {
            // Get fresh package data to ensure latest price
            $package = Package::find($detail['package']->id);
            
            if ($package) {
                $itemDetails[] = [
                    'id' => 'PKG-' . $package->id,
                    'price' => (int) $package->price, // Midtrans expects integer
                    'quantity' => $detail['quantity'],
                    'name' => $package->name
                ];
                
                Log::info('Midtrans item prepared', [
                    'package_id' => $package->id,
                    'package_name' => $package->name,
                    'price' => $package->price,
                    'quantity' => $detail['quantity']
                ]);
            }
        }
        
        return $itemDetails;
    }

    /**
     * Calculate total amount using latest prices
     */
    public function calculateTotalAmount(array $packages): float
    {
        $totalAmount = 0;
        
        foreach ($packages as $packageId => $quantity) {
            if ($quantity > 0) {
                $package = Package::find($packageId);
                if ($package && $package->is_active) {
                    $totalAmount += $package->price * $quantity;
                }
            }
        }
        
        return $totalAmount;
    }

    /**
     * Prepare package details with latest prices
     */
    public function preparePackageDetails(array $packages): array
    {
        $packageDetails = [];
        
        foreach ($packages as $packageId => $quantity) {
            if ($quantity > 0) {
                $package = Package::find($packageId);
                if ($package && $package->is_active) {
                    $subtotal = $package->price * $quantity;
                    $packageDetails[] = [
                        'package' => $package,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal
                    ];
                }
            }
        }
        
        return $packageDetails;
    }

    /**
     * Log price synchronization
     */
    public function logPriceSync(Package $package, $oldPrice, $newPrice, $userId): void
    {
        Log::info('Package price synchronized with Midtrans', [
            'package_id' => $package->id,
            'package_name' => $package->name,
            'old_price' => $oldPrice,
            'new_price' => $newPrice,
            'updated_by_user_id' => $userId,
            'midtrans_server_key' => substr(config('midtrans.server_key'), 0, 10) . '...',
            'midtrans_environment' => config('midtrans.is_production') ? 'production' : 'sandbox',
            'timestamp' => now()
        ]);
    }

    /**
     * Validate Midtrans configuration
     */
    public function validateConfiguration(): array
    {
        $errors = [];
        
        if (empty(config('midtrans.server_key'))) {
            $errors[] = 'Midtrans Server Key tidak dikonfigurasi';
        }
        
        if (empty(config('midtrans.client_key'))) {
            $errors[] = 'Midtrans Client Key tidak dikonfigurasi';
        }
        
        return $errors;
    }

    /**
     * Get Midtrans configuration status
     */
    public function getConfigurationStatus(): array
    {
        return [
            'server_key_configured' => !empty(config('midtrans.server_key')),
            'client_key_configured' => !empty(config('midtrans.client_key')),
            'environment' => config('midtrans.is_production') ? 'production' : 'sandbox',
            'is_sanitized' => config('midtrans.is_sanitized'),
            'is_3ds' => config('midtrans.is_3ds')
        ];
    }
}
