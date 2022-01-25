<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisplayOrderToAcContentPlaylists extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('ac_content_playlists', function (Blueprint $table) {
			$table->integer('display_order')->unsigned()->after('playlist_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('ac_content_playlists', function (Blueprint $table) {
			//
		});
	}

}
