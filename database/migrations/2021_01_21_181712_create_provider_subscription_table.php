<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProviderSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_subscription', function (Blueprint $table) {
             $table->increments('id');
            $table->integer('provider_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('description')->nullable();
            $table->string('amount')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();  
            $table->string('status')->default('PAID'); 
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
         Schema::dropIfExists('provider_subscription');
    }
}
