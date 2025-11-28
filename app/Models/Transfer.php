<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id','beneficiary_id','agent_id','promotion_id',
        'source_currency','target_currency','amount',
        'exchange_rate','transfer_fee','total_paid',
        'payout_amount','transfer_speed','status','completed_at'
    ];

    protected $appends = ['user_id'];

    // Virtual attribute for compatibility
    public function getUserIdAttribute()
    {
        return $this->sender_id;
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Alias for sender (for compatibility)
    public function user()
    {
        return $this->sender();
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function paymentTransaction()
    {
        return $this->hasOne(PaymentTransaction::class);
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class);
    }

    public function promotion()
{
    return $this->belongsTo(Promotion::class);
}

}
