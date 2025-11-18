<?php

namespace App\Http\Controllers;

use App\Models\TransferService;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class TransferServiceController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'destination_country' => strtoupper($request->query('destination_country', '')),
            'max_fee_percent' => $request->query('max_fee_percent'),
            'speed' => $request->query('speed'),
            'payout_method' => $request->query('payout_method'),
            'offers' => $request->boolean('offers'),
            'source_currency' => strtoupper($request->query('source_currency', 'USD')),
            'target_currency' => strtoupper($request->query('target_currency', $request->query('destination_currency', ''))),
        ];

        $query = TransferService::query()->where('is_active', true);

        if ($filters['destination_country']) {
            $country = $filters['destination_country'];
            $query->whereJsonContains('destination_countries', $country);
        }
        if ($filters['max_fee_percent'] !== null && $filters['max_fee_percent'] !== '') {
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

        $services = $query->orderBy('fee_percent')->orderBy('fee_fixed')->get();

        // Compute indicative exchange rates per service using ExchangeRate (mid-rate - margin)
        $midRate = null;
        if ($filters['source_currency'] && $filters['target_currency']) {
            $midRate = ExchangeRate::where('base_currency', $filters['source_currency'])
                ->where('target_currency', $filters['target_currency'])
                ->value('rate');
        }

        $services = $services->map(function ($svc) use ($midRate) {
            $svc->indicative_rate = null;
            if ($midRate) {
                // Reduce mid-rate by margin (bps)
                $svc->indicative_rate = round((float)$midRate * (1 - ($svc->fx_margin_bps / 10000)), 6);
            }
            return $svc;
        });

        return view('transfer-services.index', [
            'services' => $services,
            'filters' => $filters,
            'midRate' => $midRate,
        ]);
    }
}
