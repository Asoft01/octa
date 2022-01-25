<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CotdStart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_contents', function (Blueprint $table) {
            $table->dateTime('cotd_start')->nullable()->after('display_end');
            $table->dateTime('cotd_end')->nullable()->after('cotd_start');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_contents', function (Blueprint $table) {
            //
        });
    }
}
