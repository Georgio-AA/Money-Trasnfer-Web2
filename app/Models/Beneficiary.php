<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone_number',
        'email',
        'relationship',
        'country',
        'address',
        'city',
        'postal_code',
        'bank_account_id',
        'mobile_wallet_number',
        'mobile_wallet_provider',
        'preferred_payout_method'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }
}
