<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_prices', function (Blueprint $table) {
            $table->bigInteger('account_id')->unsigned()->nullable()->after('product_id');
            $table->foreign('account_id')->references('id')->on('ac_accounts');
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
