<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('domain_id');
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('price_id')->unsigned();
            $table->bigInteger('reviewer_id')->unsigned()->nullable();
            $table->bigInteger('product_id')->unsigned();
            $table->integer('product_family_id')->unsigned();
            $table->decimal('quantity', 8, 2);
            $table->decimal('unit_price', 8, 2);
            $table->timestamps();

            $table->foreign('domain_id')->references('id')->on('ac_domains');
            $table->foreign('order_id')->references('id')->on('ac_orders');
            $table->foreign('reviewer_id')->references('id')->on('ac_accounts');
            $table->foreign('price_id')->references('id')->on('ac_prices');
            $table->foreign('product_id')->references('id')->on('ac_products');
            $table->foreign('product_family_id')->references('id')->on('ac_product_families');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_order_items');
    }
}

