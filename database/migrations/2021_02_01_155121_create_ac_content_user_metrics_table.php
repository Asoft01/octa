<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcContentUserMetricsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('ac_content_user_metrics', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('user_id')->unsigned();
			$table->bigInteger('content_id')->unsigned();
			$table->integer('page_visits')->unsigned();
			$table->integer('asset_downloads')->unsigned()->nullable();
			$table->bigInteger('video_playtime')->unsigned()->nullable()->comment('milliseconds');
			$table->bigInteger('video_position')->unsigned()->nullable()->comment('milliseconds');
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
	public function down() {
		Schema::dropIfExists('ac_content_user_metrics');
	}

}
