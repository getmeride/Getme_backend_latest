<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProviderBillingInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_billing_cashout', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('provider_id')->nullable();
            $table->string('cashout_type')->nullable();
            
            $table->string('bank_deposit_full_name')->nullable();
            $table->string('bank_deposit_routing_number')->nullable();
            $table->string('bank_deposit_account_number')->nullable();
            $table->string('bank_deposit_account_type')->nullable();
            $table->string('bank_deposit_swift_code')->nullable();
            $table->string('bank_deposit_iban_number')->nullable();
            
            $table->string('pay_by_zelle_full_name')->nullable();
            $table->string('pay_by_zelle_mobile_number')->nullable();
            $table->string('pay_by_zelle_email')->nullable();


            $table->string('cashpickup_full_name')->nullable();
            $table->text('cashpickup_address')->nullable();
            $table->string('cashpickup_city_state')->nullable();
            $table->string('cashpickup_country')->nullable();
            $table->string('cashpickup_mobile_number')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
