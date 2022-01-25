<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcDomainIdToAccount extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('ac_accounts', function (Blueprint $table) {
			$table->integer('ac_domain_id')->unsigned()->nullable()->after('id');

			$table->foreign('ac_domain_id')->references('id')->on('ac_domains');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('ac_accounts');
	}

}
