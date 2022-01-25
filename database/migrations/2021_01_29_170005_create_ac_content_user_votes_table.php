<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcContentUserVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_content_user_votes', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('user_id')->unsigned();
			$table->bigInteger('content_id')->unsigned();
			$table->tinyInteger('state')->comment('-1, 0, 1');
			$table->ipAddress('ip_address'); // $table->string('ip_address');
			$table->string('http_agent');
            $table->timestamps();

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
        Schema::dropIfExists('ac_content_user_votes');
    }
}
