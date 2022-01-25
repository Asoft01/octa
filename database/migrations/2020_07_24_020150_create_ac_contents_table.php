<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_contents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('contentable');
            $table->unsignedInteger('domain_id');
            $table->text('title');
            $table->text('description');
            $table->string('slug');
            $table->timestamps();

            $table->foreign('domain_id')->references('id')->on('ac_domains');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_contents');
    }
}
