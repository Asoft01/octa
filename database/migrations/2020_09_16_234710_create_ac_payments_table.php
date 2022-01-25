<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('payment_type_id')->unsigned();
            $table->bigInteger('order_id')->unsigned();
            $table->unsignedInteger('status_id')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->string('description')->nullable();
            $table->decimal('amount', 8, 2);
            $table->string('currency')->nullable();
            $table->string('charge_id')->nullable();
            $table->unsignedInteger('charge_timestamp', 0)->nullable();
            $table->text('receipt_url')->nullable();
            $table->timestamps();

            $table->foreign('payment_type_id')->references('id')->on('ac_payment_types');
            $table->foreign('order_id')->references('id')->on('ac_orders');
            $table->foreign('status_id')->references('id')->on('ac_statuses');
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
        Schema::dropIfExists('ac_payments');
    }
}
