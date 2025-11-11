<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_id',
        'payment_method',
        'payment_reference',
        'status',
    ];

    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }
}
