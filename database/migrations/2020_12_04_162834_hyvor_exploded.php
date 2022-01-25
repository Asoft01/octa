<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HyvorExploded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_hyvors', function (Blueprint $table) {
            $table->unsignedBigInteger("hyvor_id");
            $table->unsignedBigInteger("hyvor_page_id");
            $table->longtext("markdown");
            $table->text("parent_ids")->nullable();
            $table->unsignedSmallInteger("depth");
            $table->unsignedInteger("posted_at");
            $table->unsignedInteger("upvotes");
            $table->unsignedInteger("downvotes");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("content_id");

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('content_id')->references('id')->on('ac_contents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_hyvors', function (Blueprint $table) {
            
        });
    }
}
