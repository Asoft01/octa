<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('photo');
            $table->unsignedBigInteger('user_id');
            $table->nullableMorphs('accountable');
            $table->timestamps();
        });

        Schema::table('ac_accounts', function (Blueprint $table) {
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
        Schema::dropIfExists('ac_accounts');
    }
}
