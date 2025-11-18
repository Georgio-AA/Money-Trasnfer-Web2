<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExchangeRate;

class ExchangeRateSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // USD to other currencies
            ['base_currency' => 'USD', 'target_currency' => 'EUR', 'rate' => 0.92],
            ['base_currency' => 'USD', 'target_currency' => 'GBP', 'rate' => 0.80],
            ['base_currency' => 'USD', 'target_currency' => 'CAD', 'rate' => 1.35],
            ['base_currency' => 'USD', 'target_currency' => 'AUD', 'rate' => 1.52],
            ['base_currency' => 'USD', 'target_currency' => 'PHP', 'rate' => 56.0],
            ['base_currency' => 'USD', 'target_currency' => 'INR', 'rate' => 83.0],
            ['base_currency' => 'USD', 'target_currency' => 'PKR', 'rate' => 278.0],
            ['base_currency' => 'USD', 'target_currency' => 'BDT', 'rate' => 109.5],
            ['base_currency' => 'USD', 'target_currency' => 'NGN', 'rate' => 1500.0],
            ['base_currency' => 'USD', 'target_currency' => 'KES', 'rate' => 129.0],
            ['base_currency' => 'USD', 'target_currency' => 'GHS', 'rate' => 12.5],
            ['base_currency' => 'USD', 'target_currency' => 'EGP', 'rate' => 30.9],
            ['base_currency' => 'USD', 'target_currency' => 'MXN', 'rate' => 17.2],
            ['base_currency' => 'USD', 'target_currency' => 'JPY', 'rate' => 149.5],
            ['base_currency' => 'USD', 'target_currency' => 'CNY', 'rate' => 7.24],
            ['base_currency' => 'USD', 'target_currency' => 'BRL', 'rate' => 4.98],
            ['base_currency' => 'USD', 'target_currency' => 'ZAR', 'rate' => 18.5],
            ['base_currency' => 'USD', 'target_currency' => 'AED', 'rate' => 3.67],
            ['base_currency' => 'USD', 'target_currency' => 'SAR', 'rate' => 3.75],
            ['base_currency' => 'USD', 'target_currency' => 'LBP', 'rate' => 89500.0],
            
            // EUR to other currencies
            ['base_currency' => 'EUR', 'target_currency' => 'USD', 'rate' => 1.09],
            ['base_currency' => 'EUR', 'target_currency' => 'GBP', 'rate' => 0.87],
            ['base_currency' => 'EUR', 'target_currency' => 'INR', 'rate' => 90.3],
            ['base_currency' => 'EUR', 'target_currency' => 'PHP', 'rate' => 60.9],
            
            // GBP to other currencies
            ['base_currency' => 'GBP', 'target_currency' => 'USD', 'rate' => 1.25],
            ['base_currency' => 'GBP', 'target_currency' => 'EUR', 'rate' => 1.15],
            ['base_currency' => 'GBP', 'target_currency' => 'INR', 'rate' => 103.7],
            ['base_currency' => 'GBP', 'target_currency' => 'PKR', 'rate' => 347.5],
        ];
        
        foreach ($rows as $r) {
            ExchangeRate::updateOrCreate([
                'base_currency' => $r['base_currency'],
                'target_currency' => $r['target_currency'],
            ], [
                'rate' => $r['rate'],
            ]);
        }
    }
}
