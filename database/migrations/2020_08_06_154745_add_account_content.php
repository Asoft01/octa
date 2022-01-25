<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_reviews', function (Blueprint $table) {
            $table->bigInteger('mentor_id')->unsigned()->nullable()->after('id');
            $table->bigInteger('artist_id')->unsigned()->nullable()->after('mentor_id');
        });

        Schema::table('ac_reviews', function (Blueprint $table) {
            $table->foreign('mentor_id')->references('id')->on('ac_accounts');
            $table->foreign('artist_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_reviews', function (Blueprint $table) {
            //
        });
    }
}
