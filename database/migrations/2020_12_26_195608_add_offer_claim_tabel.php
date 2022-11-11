<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOfferClaimTabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_claims', function (Blueprint $table) {
            //
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('offer_id');
        });
        Schema::table('offer_claims', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('offer_id')->references('id')->on('offers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offer_claims', function (Blueprint $table) {
            //
        });
    }
}
