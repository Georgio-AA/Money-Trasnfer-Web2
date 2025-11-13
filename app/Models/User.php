<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
                    'name',
                'surname',
                'age',
                'email',
                'phone',
                'password',
                'verification_token',
                'verification_due',
                'is_verified',
                'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'verification_due' => 'datetime',
        'is_verified' => 'boolean',

        'password' => 'hashed',
    ];

    public function bankAccounts()
{
    return $this->hasMany(BankAccount::class);
}

public function beneficiaries()
{
    return $this->hasMany(Beneficiary::class);
}

public function sentTransfers()
{
    return $this->hasMany(Transfer::class, 'sender_id');
}

public function agentProfile()
{
    return $this->hasOne(Agent::class);
}

    
}
