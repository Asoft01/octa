<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_terms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('version');
            $table->text('content');
            $table->unsignedInteger('domain_id');
            $table->unsignedInteger('language_id');
            $table->timestamps();

            $table->foreign('domain_id')->references('id')->on('ac_domains');
            $table->foreign('language_id')->references('id')->on('ac_languages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_terms');
    }
}
