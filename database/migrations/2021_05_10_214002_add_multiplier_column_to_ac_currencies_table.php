<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultiplierColumnToAcCurrenciesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('ac_currencies', function (Blueprint $table) {
			$table->double('multiplier')->after('symbol');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('ac_currencies', function (Blueprint $table) {
			//
		});
	}

}
