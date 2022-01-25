<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStreamIdStreamStartTimeStreamEndTimeToAcOrderItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_order_items', function (Blueprint $table) {
            $table->bigInteger('stream_id')->nullable();
            $table->dateTime('stream_start_time')->nullable();
            $table->dateTime('stream_end_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_order_items', function (Blueprint $table) {
            //
        });
    }
}
