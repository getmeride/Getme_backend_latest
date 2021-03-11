<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProviderSubscription extends Model
{
    protected $table='provider_subscription';
    protected $fillable = [
        'id','provider_id', 'transaction_id', 'description', 'amount','start_date','end_date','status','created_at','updated_at'
    ];
    public function providerInfo()
    {
        return $this->hasOne('App\Provider','id','provider_id');
    }
}
