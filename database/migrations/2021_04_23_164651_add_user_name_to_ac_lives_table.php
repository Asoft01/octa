<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserNameToAcLivesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('ac_lives', function (Blueprint $table) {
			$table->text('user_name')->nullable()->after('user_login');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('ac_lives', function (Blueprint $table) {
			//
		});
	}

}
