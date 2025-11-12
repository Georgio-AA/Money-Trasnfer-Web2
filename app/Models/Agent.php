<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_name',
        'address',
        'country',
        'latitude',
        'longitude',
        'working_hours',
        'commission_rate',
        'approved',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transfers()
    {
        // Transfers store agent_id as the User's id; Agent model links to User via user_id
        return $this->hasMany(Transfer::class, 'agent_id', 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
