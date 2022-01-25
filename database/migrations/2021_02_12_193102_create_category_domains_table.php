<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryDomainsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('ac_category_domains', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('ac_category_id')->unsigned();
			$table->integer('ac_domain_id')->unsigned();
			$table->timestamps();

			$table->foreign('ac_category_id')->references('id')->on('ac_categories');
			$table->foreign('ac_domain_id')->references('id')->on('ac_domains');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('ac_category_domains');
	}

}
