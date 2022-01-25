<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDelayToAcAccounts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('ac_accounts', function (Blueprint $table) {
			$table->integer('delay')->unsigned()->nullable()->comment('days')->after('hoursWeek');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('ac_accounts', function (Blueprint $table) {
			//
		});
	}

}
