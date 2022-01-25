<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderAddBilling extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_orders', function (Blueprint $table) {
           $table->string('name')->nullable()->after('amount_paid'); 
           $table->string('address')->nullable()->after('name'); 
           $table->string('city')->nullable()->after('address');
           $table->string('province')->nullable()->after('city');
           $table->string('postalcode')->nullable()->after('province');
           $table->string('country')->nullable()->after('postalcode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_orders', function (Blueprint $table) {
            //
        });
    }
}
