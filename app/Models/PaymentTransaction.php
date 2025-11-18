<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_id',
        'user_id',
        'transaction_type',
        'amount',
        'currency',
        'payment_method',
        'payment_reference',
        'status',
        'description',
    ];

    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
