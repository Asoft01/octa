<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return voidac
     */
    public function up()
    {
        Schema::create('ac_deliveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('order_item_id')->unsigned();
            $table->unsignedInteger('status_id')->nullable();
            $table->unsignedInteger('unit_id');
            $table->decimal('quantity_sold', 8, 2);
            $table->decimal('quantity_delivered', 8, 2)->nullable();
            $table->date('due_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('reviewer_id')->unsigned();
            $table->string('videoToReview')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('product_family_id');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('ac_orders');
            $table->foreign('order_item_id')->references('id')->on('ac_order_items');
            $table->foreign('status_id')->references('id')->on('ac_statuses');
            $table->foreign('reviewer_id')->references('id')->on('ac_accounts');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('unit_id')->references('id')->on('ac_units');
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
        Schema::dropIfExists('ac_deliveries');
    }
}
