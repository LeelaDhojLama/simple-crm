<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSalesIdToOfferClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offer_claims', function (Blueprint $table) {
            //
            $table->bigInteger('sales_id')->unsigned();
        });

        Schema::table('offer_claims', function (Blueprint $table) {
            $table->foreign('sales_id')->references('id')->on('sales');
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
