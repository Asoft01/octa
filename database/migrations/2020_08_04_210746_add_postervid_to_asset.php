<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPostervidToAsset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_assets', function (Blueprint $table) {
            $table->string('poster')->after('filesize');
            $table->string('preview_video')->after('poster');
            $table->string('video')->after('preview_video');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_assets', function (Blueprint $table) {
            //
        });
    }
}
