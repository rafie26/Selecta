<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateMidtransConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'midtrans:update-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Midtrans configuration with correct sandbox credentials';

    /**
     * The sandbox credentials
     *
     * @var array
     */
    protected $sandboxCredentials = [
        'MIDTRANS_SERVER_KEY' => 'SB-Mid-server-GwS6LjPnpotNiagCOBXBzqNB',
        'MIDTRANS_CLIENT_KEY' => 'SB-Mid-client-nKsqvar5cn60u2Lv',
        'MIDTRANS_IS_PRODUCTION' => 'false'
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $envPath = base_path('.env');
        
        if (!file_exists($envPath)) {
            $this->error('.env file not found!');
            return 1;
        }
        
        // Read current .env file
        $envContent = file_get_contents($envPath);
        $updated = false;
        
        // Update each Midtrans setting
        foreach ($this->sandboxCredentials as $key => $value) {
            if (str_contains($envContent, $key)) {
                // Update existing key
                $envContent = preg_replace(
                    "/^" . $key . "=.*/m",
                    $key . '=' . $value,
                    $envContent
                );
            } else {
                // Add new key
                $envContent .= PHP_EOL . $key . '=' . $value;
            }
            $updated = true;
        }
        
        if ($updated) {
            // Write back to .env
            file_put_contents($envPath, $envContent);
            
            // Clear config cache
            $this->call('config:clear');
            
            $this->info('✅ Midtrans configuration updated successfully!');
            $this->line('');
            $this->line('Updated configuration:');
            foreach ($this->sandboxCredentials as $key => $value) {
                $this->line("<fg=green>{$key}=${value}");
            }
        } else {
            $this->info('No changes were made to the configuration.');
        }
    }
}
