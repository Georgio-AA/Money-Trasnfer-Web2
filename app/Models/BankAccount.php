<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_name',
        'account_number',
        'account_type',
        'currency',
        'is_verified',
        'verification_document',
        'micro_amount_1',
        'micro_amount_2',
        'micro_transfer_sent_at',
        'verification_expires_at',
        'verification_attempts'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'micro_transfer_sent_at' => 'datetime',
        'verification_expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the masked account number for display
     */
    public function getMaskedAccountNumberAttribute()
    {
        return '****' . substr($this->account_number, -4);
    }

    /**
     * Get the formatted currency display
     */
    public function getCurrencyDisplayAttribute()
    {
        $currencies = [
            'USD' => 'US Dollar',
            'EUR' => 'Euro',
            'GBP' => 'British Pound',
            'JPY' => 'Japanese Yen',
            'CAD' => 'Canadian Dollar',
            'AUD' => 'Australian Dollar',
            'CHF' => 'Swiss Franc',
            'CNY' => 'Chinese Yuan',
            'SEK' => 'Swedish Krona',
            'NZD' => 'New Zealand Dollar',
            'MXN' => 'Mexican Peso',
            'SGD' => 'Singapore Dollar',
            'HKD' => 'Hong Kong Dollar',
            'NOK' => 'Norwegian Krone',
            'KRW' => 'South Korean Won',
            'TRY' => 'Turkish Lira',
            'RUB' => 'Russian Ruble',
            'INR' => 'Indian Rupee',
            'BRL' => 'Brazilian Real',
            'ZAR' => 'South African Rand'
        ];

        return $this->currency . ' - ' . ($currencies[$this->currency] ?? $this->currency);
    }
}
