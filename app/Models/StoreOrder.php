<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'price_at_purchase',
        'generated_code',
        'status',
    ];

    protected $casts = [
        'price_at_purchase' => 'decimal:2',
    ];

    /**
     * Get the user who made this purchase
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that was purchased
     */
    public function product()
    {
        return $this->belongsTo(StoreProduct::class, 'product_id');
    }

    /**
     * Scope: Get orders by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Get orders for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
