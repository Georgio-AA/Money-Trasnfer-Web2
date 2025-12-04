<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExchangeRate;

class ExchangeRateSeeder extends Seeder
{
    public function run(): void
    {
        $currencies = ['USD', 'EUR', 'GBP', 'JPY', 'CAD', 'AUD', 'CHF', 'CNY', 'SEK', 'NZD', 'MXN', 'SGD', 'HKD', 'NOK', 'KRW', 'TRY', 'RUB', 'INR', 'BRL', 'ZAR', 'LBP', 'SAR', 'AED', 'EGP', 'PKR', 'BDT', 'THB', 'VND', 'PHP', 'IDR', 'MYR'];
        
        // Exchange rates relative to 1 USD
        $rates = [
            'USD' => 1,
            'EUR' => 0.92,
            'GBP' => 0.79,
            'JPY' => 149.50,
            'CAD' => 1.36,
            'AUD' => 1.53,
            'CHF' => 0.88,
            'CNY' => 7.24,
            'SEK' => 10.87,
            'NZD' => 1.67,
            'MXN' => 17.12,
            'SGD' => 1.35,
            'HKD' => 7.83,
            'NOK' => 10.92,
            'KRW' => 1320.50,
            'TRY' => 32.15,
            'RUB' => 92.50,
            'INR' => 83.12,
            'BRL' => 4.97,
            'ZAR' => 18.65,
            'LBP' => 89500,
            'SAR' => 3.75,      // Saudi Riyal
            'AED' => 3.67,      // UAE Dirham
            'EGP' => 30.90,     // Egyptian Pound
            'PKR' => 278.50,    // Pakistani Rupee
            'BDT' => 110.50,    // Bangladeshi Taka
            'THB' => 35.80,     // Thai Baht
            'VND' => 24500,     // Vietnamese Dong
            'PHP' => 56.20,     // Philippine Peso
            'IDR' => 15700,     // Indonesian Rupiah
            'MYR' => 4.68       // Malaysian Ringgit
        ];

        $count = 0;
        foreach ($currencies as $base) {
            foreach ($currencies as $target) {
                if ($base !== $target) {
                    // Calculate cross rate: how much target currency you get for 1 base currency
                    $rate = $rates[$target] / $rates[$base];
                    
                    ExchangeRate::updateOrCreate(
                        [
                            'base_currency' => $base,
                            'target_currency' => $target
                        ],
                        [
                            'rate' => $rate
                        ]
                    );
                    $count++;
                }
            }
        }
        
        echo "Created/Updated {$count} exchange rates for all currency pairs.\n";
    }
}
