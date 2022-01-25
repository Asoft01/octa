<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->unsignedInteger('domain_id');
            $table->bigInteger('user_id')->unsigned();
            $table->unsignedInteger('status_order_id')->nullable();
            $table->unsignedInteger('status_delivery_id')->nullable();
            $table->unsignedInteger('status_payment_id')->nullable();
            $table->decimal('amount_df', 8, 2)->nullable();
            $table->decimal('amount_pst', 8, 2)->nullable();
            $table->decimal('amount_qst', 8, 2)->nullable();
            $table->decimal('amount_gst', 8, 2)->nullable();
            $table->decimal('amount_hst', 8, 2)->nullable();
            $table->decimal('amount_total', 8, 2)->nullable();
            $table->decimal('amount_paid', 8, 2)->nullable();
            $table->timestamps();

            $table->foreign('domain_id')->references('id')->on('ac_domains');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_orders');
    }
}
