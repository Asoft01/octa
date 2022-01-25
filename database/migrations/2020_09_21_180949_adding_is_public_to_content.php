<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingIsPublicToContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_contents', function (Blueprint $table) {
            $table->boolean('isPublic')->nullable()->after('slug');
            $table->unsignedBigInteger('delivery_id')->nullable()->after('isPublic');

            $table->foreign('delivery_id')->references('id')->on('ac_deliveries');
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
