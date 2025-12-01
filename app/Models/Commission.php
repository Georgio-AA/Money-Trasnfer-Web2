<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Commission Model
 * 
 * Tracks commission earnings for agents on individual transfers.
 * Each commission record links an agent to a specific transfer with
 * commission amount and calculation details.
 */
class Commission extends Model
{
    use HasFactory;

    protected $table = 'agent_commissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'agent_id',
        'transfer_id',
        'commission_amount',
        'commission_rate',
        'calculation_method',
        'transfer_amount',
        'status',
        'paid_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'commission_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'transfer_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the agent that owns this commission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    /**
     * Get the transfer associated with this commission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transfer()
    {
        return $this->belongsTo(Transfer::class, 'transfer_id');
    }

    /**
     * Scope: Get commissions within a date range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \DateTime|string $startDate
     * @param \DateTime|string $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope: Get commissions for a specific agent.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $agentId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    /**
     * Scope: Get commissions by status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Get pending commissions (not yet paid).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get approved commissions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Get paid commissions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
