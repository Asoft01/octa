<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AccountsMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_accounts', function (Blueprint $table) {
            $table->text('cv')->after('bio')->nullable();
            $table->string('preview_video')->after('photo')->nullable();
            $table->string('video')->after('preview_video')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_accounts', function (Blueprint $table) {
            //
        });
    }
}
