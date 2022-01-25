<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('domain_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->text('title');

            $table->foreign('domain_id')->references('id')->on('ac_domains');
            $table->foreign('category_id')->references('id')->on('ac_categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_tags');
    }
}
