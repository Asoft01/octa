<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDelivery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_deliveries', function (Blueprint $table) {
            $table->string('mimeType')->nullable()->after('videoToReview');
            $table->integer('size')->unsigned()->nullable()->after('mimeType');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_deliveries', function (Blueprint $table) {
            //
        });
    }
}
