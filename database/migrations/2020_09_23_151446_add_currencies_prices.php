<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrenciesPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_prices', function (Blueprint $table) {
            $table->unsignedInteger('currency_id')->after("product_id")->default(1);
            $table->foreign('currency_id')->references('id')->on('ac_currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_prices', function (Blueprint $table) {
            //
        });
    }
}
