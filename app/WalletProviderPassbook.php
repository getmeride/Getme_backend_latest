<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletProviderPassbook extends Model
{
    
     protected $table='wallet_provider_passbooks';  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_id', 'amount', 'status', 'via',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at'
    ];
}
