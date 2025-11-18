<?php

namespace App\Http\Controllers;

use App\Models\TransferService;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class TransferServiceController extends Controller
{
    public function index(Request $request)
    {
        $speeds = config('transfer.speeds');
        $payoutMethods = config('transfer.payout_methods');
        $countries = config('transfer.countries');

        // Raw inputs
        $rawCountry = trim((string)$request->query('destination_country', ''));
        $rawSpeed = trim((string)$request->query('speed', ''));
        $rawPayout = trim((string)$request->query('payout_method', ''));
        $rawFeesRates = trim((string)$request->query('fees_rates', ''));

        // Normalize country: allow 2-letter code or full name (case-insensitive), ignore 'any'
        $destinationCountry = '';
        if ($rawCountry !== '' && strcasecmp($rawCountry, 'any') !== 0) {
            $codeCandidate = strtoupper($rawCountry);
            if (isset($countries[$codeCandidate])) {
                $destinationCountry = $codeCandidate;
            } else {
                // Match by name (case-insensitive, allow partial starts-with)
                $lower = mb_strtolower($rawCountry);
                foreach ($countries as $code => $name) {
                    $lname = mb_strtolower($name);
                    if ($lname === $lower || str_starts_with($lname, $lower)) {
                        $destinationCountry = $code;
                        break;
                    }
                }
            }
        }

        // Normalize speed/payout: allow 'any' to disable
        $speed = (strcasecmp($rawSpeed, 'any') === 0) ? '' : $rawSpeed;
        $payout = (strcasecmp($rawPayout, 'any') === 0) ? '' : $rawPayout;

        // Parse free-form "fees & exchange rates" input:
        // - Numeric like "5" or "5%" -> max fee percent
        // - Keywords like "cheap", "low", "best rate" -> prefer sorting but do not hard-filter
        $maxFeePercent = null;
        $feesKeyword = null;
        if ($rawFeesRates !== '') {
            if (preg_match('/([0-9]+(?:\.[0-9]+)?)\s*%?/', $rawFeesRates, $m)) {
                $maxFeePercent = (float)$m[1];
            } else {
                $lower = mb_strtolower($rawFeesRates);
                if (str_contains($lower, 'cheap') || str_contains($lower, 'low') || str_contains($lower, 'best')) {
                    $feesKeyword = 'low';
                }
                if (str_contains($lower, 'high') || str_contains($lower, 'expens')) {
                    $feesKeyword = 'high';
                }
            }
        }

        $filters = [
            'destination_country' => $destinationCountry,
            'max_fee_percent' => $maxFeePercent,
            'fees_rates' => $rawFeesRates,
            'speed' => $speed,
            'payout_method' => $payout,
            'offers' => $request->boolean('offers'),
            // Keep only the requested fields; no amount/currencies here
        ];

        $query = TransferService::query()->where('is_active', true);

        if ($filters['destination_country']) {
            $country = $filters['destination_country'];
            $query->whereJsonContains('destination_countries', $country);
        }
        if ($filters['max_fee_percent'] !== null) {
            $query->where('fee_percent', '<=', (float)$filters['max_fee_percent']);
        }
        if ($filters['speed']) {
            $query->where('transfer_speed', $filters['speed']);
        }
        if ($filters['payout_method']) {
            $query->whereJsonContains('payout_methods', $filters['payout_method']);
        }
        if ($filters['offers']) {
            $query->where('has_promotions', true);
        }

        // Apply sorting preferences based on keywords if provided
        if ($feesKeyword === 'low') {
            $query->orderBy('fee_percent')->orderBy('fee_fixed');
        } elseif ($feesKeyword === 'high') {
            $query->orderByDesc('fee_percent')->orderByDesc('fee_fixed');
        } else {
            $query->orderBy('fee_percent')->orderBy('fee_fixed');
        }

        $services = $query->get();

        // Compute indicative exchange rates per service using ExchangeRate (mid-rate - margin)
        // Map basic display helpers
        $services = $services->map(function ($svc) {
            // No currency math here; show FX margin percentage instead
            $svc->fx_margin_percent = round(($svc->fx_margin_bps ?? 0) / 100, 2);
            return $svc;
        });

        // If no exact matches, suggest some providers without strict filters
        $suggested = collect();
        if ($services->isEmpty()) {
            $suggested = \App\Models\TransferService::query()
                ->where('is_active', true)
                ->orderBy('fee_percent')
                ->orderBy('fee_fixed')
                ->limit(6)
                ->get()
                ->map(function ($svc) {
                    $svc->fx_margin_percent = round(($svc->fx_margin_bps ?? 0) / 100, 2);
                    return $svc;
                });
        }

        return view('transfer-services.index', [
            'services' => $services,
            'filters' => $filters,
            'speeds' => $speeds,
            'payoutMethods' => $payoutMethods,
            'countries' => $countries,
            'suggested' => $suggested,
        ]);
    }
}
