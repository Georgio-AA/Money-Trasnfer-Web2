<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'provider',
        'category',
        'price',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * Get all orders for this product
     */
    public function orders()
    {
        return $this->hasMany(StoreOrder::class, 'product_id');
    }

    /**
     * Scope: Get only active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get products by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope: Get products by provider
     */
    public function scopeByProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }
}
