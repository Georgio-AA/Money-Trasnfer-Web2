<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'destination_countries',
        'fee_fixed',
        'fee_percent',
        'fx_margin_bps',
        'transfer_speed',
        'payout_methods',
        'has_promotions',
        'is_active',
    ];

    protected $casts = [
        'destination_countries' => 'array',
        'payout_methods' => 'array',
        'has_promotions' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Simple helper to check support
    public function supportsCountry(string $countryCode): bool
    {
        return in_array(strtoupper($countryCode), $this->destination_countries ?? []);
    }

    public function supportsPayout(string $method): bool
    {
        return in_array(strtolower($method), array_map('strtolower', $this->payout_methods ?? []));
    }
}
