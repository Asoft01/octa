<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_streams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('reviewer_id')->unsigned();
            $table->date('start_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->timestamps();
            $table->foreign('reviewer_id')->references('id')->on('ac_accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_streams');
    }
}
