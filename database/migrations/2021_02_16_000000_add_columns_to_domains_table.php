<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToDomainsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('ac_domains', function (Blueprint $table) {
			$table->string('slug')->unique()->nullable()->after('id');
			$table->bigInteger('announcements_category_id')->unsigned()->nullable()->after('id');

			$table->foreign('announcements_category_id')->references('id')->on('ac_categories');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('ac_domains');
	}

}
