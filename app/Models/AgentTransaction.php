<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentTransaction extends Model
{
    use HasFactory;

    protected $table = 'agent_transactions';

    protected $fillable = [
        'agent_id',
        'transfer_id',
        'type',
        'amount',
        'currency',
        'status',
    ];

    // Relationships

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }
}
