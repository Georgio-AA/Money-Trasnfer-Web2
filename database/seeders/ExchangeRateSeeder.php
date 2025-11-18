<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExchangeRate;

class ExchangeRateSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['base_currency' => 'USD', 'target_currency' => 'EUR', 'rate' => 0.92],
            ['base_currency' => 'USD', 'target_currency' => 'GBP', 'rate' => 0.80],
            ['base_currency' => 'USD', 'target_currency' => 'PHP', 'rate' => 56.0],
            ['base_currency' => 'USD', 'target_currency' => 'NGN', 'rate' => 1500.0],
            ['base_currency' => 'USD', 'target_currency' => 'INR', 'rate' => 83.0],
            ['base_currency' => 'USD', 'target_currency' => 'AED', 'rate' => 3.67],
        ];
        foreach ($rows as $r) {
            ExchangeRate::updateOrCreate([
                'base_currency' => $r['base_currency'],
                'target_currency' => $r['target_currency'],
            ], ['rate' => $r['rate']]);
        }
    }
}
