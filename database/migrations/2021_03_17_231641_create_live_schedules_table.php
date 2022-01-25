<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveSchedulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('ac_live_schedules', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('account_id')->unsigned()->nullable();
			$table->string('slug');
			$table->text('title');
			//$table->text('excerpt');
			$table->text('description');
			$table->dateTime('eventDatetime');
			$table->integer('eventDuration')->comment('minutes');
			$table->timestamps();

			$table->foreign('account_id')->references('id')->on('ac_accounts');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('live_schedules');
	}

}
