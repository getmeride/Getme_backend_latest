<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProviderBillingCashout extends Model
{
    protected $table='provider_billing_cashout';
   	protected $guarded = [];
    // protected $fillable = [
    //     'id','provider_id', 'transaction_id', 'description', 'amount','start_date','end_date','status','created_at','updated_at'
    // ];

}
