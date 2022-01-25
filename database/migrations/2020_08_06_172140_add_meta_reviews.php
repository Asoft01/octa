<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetaReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_reviews', function (Blueprint $table) {
            $table->time('length')->nullable()->after('syncsketch');
            $table->date('releaseDate')->nullable()->after('length');
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
