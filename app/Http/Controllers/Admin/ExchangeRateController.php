<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ExchangeRateController extends Controller
{
    protected $ratesFile;
    protected $feesFile;

    public function __construct()
    {
        $this->ratesFile = storage_path('app/exchange_rates.json');
        $this->feesFile = storage_path('app/fee_structures.json');
        
        // Create default files if they don't exist
        if (!File::exists($this->ratesFile)) {
            $this->createDefaultRates();
        }
        
        if (!File::exists($this->feesFile)) {
            $this->createDefaultFees();
        }
    }

    public function index()
    {
        $rates = $this->getRates();
        $fees = $this->getFees();
        $currencies = $this->getSupportedCurrencies();
        
        return view('admin.exchange-rates', compact('rates', 'fees', 'currencies'));
    }

    // Exchange Rate Management
    public function updateRate(Request $request)
    {
        $request->validate([
            'from_currency' => 'required|string|size:3',
            'to_currency' => 'required|string|size:3',
            'rate' => 'required|numeric|min:0',
        ]);

        $rates = $this->getRates();
        $key = $request->from_currency . '_' . $request->to_currency;
        
        $rates[$key] = [
            'from' => strtoupper($request->from_currency),
            'to' => strtoupper($request->to_currency),
            'rate' => (float) $request->rate,
            'updated_at' => now()->toDateTimeString(),
        ];

        File::put($this->ratesFile, json_encode($rates, JSON_PRETTY_PRINT));

        return back()->with('success', 'Exchange rate updated successfully');
    }

    public function deleteRate($key)
    {
        $rates = $this->getRates();
        
        if (isset($rates[$key])) {
            unset($rates[$key]);
            File::put($this->ratesFile, json_encode($rates, JSON_PRETTY_PRINT));
            return back()->with('success', 'Exchange rate deleted successfully');
        }

        return back()->with('error', 'Exchange rate not found');
    }

    // Fee Structure Management
    public function updateFee(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|size:3',
            'fee_type' => 'required|in:percentage,fixed,tiered',
            'fee_percentage' => 'nullable|numeric|min:0|max:100',
            'fee_fixed' => 'nullable|numeric|min:0',
            'min_fee' => 'nullable|numeric|min:0',
            'max_fee' => 'nullable|numeric|min:0',
        ]);

        $fees = $this->getFees();
        $currency = strtoupper($request->currency);
        
        $feeData = [
            'currency' => $currency,
            'fee_type' => $request->fee_type,
            'fee_percentage' => $request->fee_percentage ? (float) $request->fee_percentage : 0,
            'fee_fixed' => $request->fee_fixed ? (float) $request->fee_fixed : 0,
            'min_fee' => $request->min_fee ? (float) $request->min_fee : 0,
            'max_fee' => $request->max_fee ? (float) $request->max_fee : null,
            'updated_at' => now()->toDateTimeString(),
        ];

        // Handle tiered fees if provided
        if ($request->fee_type === 'tiered' && $request->has('tiers')) {
            $feeData['tiers'] = $request->tiers;
        }

        $fees[$currency] = $feeData;

        File::put($this->feesFile, json_encode($fees, JSON_PRETTY_PRINT));

        return back()->with('success', 'Fee structure updated successfully');
    }

    public function deleteFee($currency)
    {
        $fees = $this->getFees();
        
        if (isset($fees[$currency])) {
            unset($fees[$currency]);
            File::put($this->feesFile, json_encode($fees, JSON_PRETTY_PRINT));
            return back()->with('success', 'Fee structure deleted successfully');
        }

        return back()->with('error', 'Fee structure not found');
    }

    // Bulk update from external API (optional)
    public function syncRates(Request $request)
    {
        // This would typically fetch from an external API like exchangerate-api.com
        // For now, we'll just add a manual sync functionality
        
        return back()->with('info', 'Manual sync completed. In production, this would fetch from an external API.');
    }

    // Helper Methods
    protected function getRates()
    {
        if (File::exists($this->ratesFile)) {
            return json_decode(File::get($this->ratesFile), true);
        }
        
        return $this->createDefaultRates();
    }

    protected function getFees()
    {
        if (File::exists($this->feesFile)) {
            return json_decode(File::get($this->feesFile), true);
        }
        
        return $this->createDefaultFees();
    }

    protected function createDefaultRates()
    {
        $defaults = [
            'USD_EUR' => ['from' => 'USD', 'to' => 'EUR', 'rate' => 0.92, 'updated_at' => now()->toDateTimeString()],
            'USD_GBP' => ['from' => 'USD', 'to' => 'GBP', 'rate' => 0.79, 'updated_at' => now()->toDateTimeString()],
            'USD_CAD' => ['from' => 'USD', 'to' => 'CAD', 'rate' => 1.36, 'updated_at' => now()->toDateTimeString()],
            'USD_AUD' => ['from' => 'USD', 'to' => 'AUD', 'rate' => 1.52, 'updated_at' => now()->toDateTimeString()],
            'USD_JPY' => ['from' => 'USD', 'to' => 'JPY', 'rate' => 149.50, 'updated_at' => now()->toDateTimeString()],
            'USD_LBP' => ['from' => 'USD', 'to' => 'LBP', 'rate' => 89500.00, 'updated_at' => now()->toDateTimeString()],
            'EUR_USD' => ['from' => 'EUR', 'to' => 'USD', 'rate' => 1.09, 'updated_at' => now()->toDateTimeString()],
            'EUR_GBP' => ['from' => 'EUR', 'to' => 'GBP', 'rate' => 0.86, 'updated_at' => now()->toDateTimeString()],
            'EUR_LBP' => ['from' => 'EUR', 'to' => 'LBP', 'rate' => 97500.00, 'updated_at' => now()->toDateTimeString()],
            'GBP_USD' => ['from' => 'GBP', 'to' => 'USD', 'rate' => 1.27, 'updated_at' => now()->toDateTimeString()],
            'GBP_EUR' => ['from' => 'GBP', 'to' => 'EUR', 'rate' => 1.16, 'updated_at' => now()->toDateTimeString()],
            'LBP_USD' => ['from' => 'LBP', 'to' => 'USD', 'rate' => 0.000011, 'updated_at' => now()->toDateTimeString()],
            'LBP_EUR' => ['from' => 'LBP', 'to' => 'EUR', 'rate' => 0.000010, 'updated_at' => now()->toDateTimeString()],
        ];

        File::put($this->ratesFile, json_encode($defaults, JSON_PRETTY_PRINT));
        
        return $defaults;
    }

    protected function createDefaultFees()
    {
        $defaults = [
            'USD' => [
                'currency' => 'USD',
                'fee_type' => 'percentage',
                'fee_percentage' => 2.5,
                'fee_fixed' => 1.00,
                'min_fee' => 1.00,
                'max_fee' => 50.00,
                'updated_at' => now()->toDateTimeString(),
            ],
            'EUR' => [
                'currency' => 'EUR',
                'fee_type' => 'percentage',
                'fee_percentage' => 2.5,
                'fee_fixed' => 0.90,
                'min_fee' => 0.90,
                'max_fee' => 45.00,
                'updated_at' => now()->toDateTimeString(),
            ],
            'GBP' => [
                'currency' => 'GBP',
                'fee_type' => 'percentage',
                'fee_percentage' => 2.5,
                'fee_fixed' => 0.80,
                'min_fee' => 0.80,
                'max_fee' => 40.00,
                'updated_at' => now()->toDateTimeString(),
            ],
            'CAD' => [
                'currency' => 'CAD',
                'fee_type' => 'percentage',
                'fee_percentage' => 2.5,
                'fee_fixed' => 1.35,
                'min_fee' => 1.35,
                'max_fee' => 65.00,
                'updated_at' => now()->toDateTimeString(),
            ],
            'LBP' => [
                'currency' => 'LBP',
                'fee_type' => 'percentage',
                'fee_percentage' => 3.0,
                'fee_fixed' => 100000.00,
                'min_fee' => 50000.00,
                'max_fee' => 5000000.00,
                'updated_at' => now()->toDateTimeString(),
            ],
        ];

        File::put($this->feesFile, json_encode($defaults, JSON_PRETTY_PRINT));
        
        return $defaults;
    }

    protected function getSupportedCurrencies()
    {
        return [
            'USD' => 'US Dollar',
            'EUR' => 'Euro',
            'GBP' => 'British Pound',
            'CAD' => 'Canadian Dollar',
            'AUD' => 'Australian Dollar',
            'JPY' => 'Japanese Yen',
            'LBP' => 'Lebanese Pound',
            'CHF' => 'Swiss Franc',
            'CNY' => 'Chinese Yuan',
            'INR' => 'Indian Rupee',
            'MXN' => 'Mexican Peso',
        ];
    }

    // Calculate fee for a transfer
    public static function calculateFee($amount, $currency)
    {
        $feesFile = storage_path('app/fee_structures.json');
        
        if (!File::exists($feesFile)) {
            return $amount * 0.025; // Default 2.5%
        }

        $fees = json_decode(File::get($feesFile), true);
        
        if (!isset($fees[$currency])) {
            return $amount * 0.025; // Default 2.5%
        }

        $feeStructure = $fees[$currency];
        $calculatedFee = 0;

        switch ($feeStructure['fee_type']) {
            case 'percentage':
                $calculatedFee = ($amount * $feeStructure['fee_percentage']) / 100;
                break;
                
            case 'fixed':
                $calculatedFee = $feeStructure['fee_fixed'];
                break;
                
            case 'tiered':
                // Implement tiered fee calculation if tiers are defined
                if (isset($feeStructure['tiers'])) {
                    foreach ($feeStructure['tiers'] as $tier) {
                        if ($amount >= $tier['min'] && $amount <= $tier['max']) {
                            $calculatedFee = ($amount * $tier['percentage']) / 100;
                            break;
                        }
                    }
                } else {
                    $calculatedFee = ($amount * $feeStructure['fee_percentage']) / 100;
                }
                break;
        }

        // Apply min/max fee limits
        if (isset($feeStructure['min_fee']) && $calculatedFee < $feeStructure['min_fee']) {
            $calculatedFee = $feeStructure['min_fee'];
        }

        if (isset($feeStructure['max_fee']) && $feeStructure['max_fee'] > 0 && $calculatedFee > $feeStructure['max_fee']) {
            $calculatedFee = $feeStructure['max_fee'];
        }

        return round($calculatedFee, 2);
    }

    // Get exchange rate
    public static function getRate($fromCurrency, $toCurrency)
    {
        $ratesFile = storage_path('app/exchange_rates.json');
        
        if (!File::exists($ratesFile)) {
            return 1.0; // Default rate
        }

        $rates = json_decode(File::get($ratesFile), true);
        $key = $fromCurrency . '_' . $toCurrency;

        if (isset($rates[$key])) {
            return (float) $rates[$key]['rate'];
        }

        return 1.0; // Default rate if not found
    }
}
