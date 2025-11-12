<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'discount_percent',
        'valid_from',
        'valid_to',
        'active',
    ];

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }
}
