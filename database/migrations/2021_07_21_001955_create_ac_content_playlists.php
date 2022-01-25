<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcContentPlaylists extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('ac_content_playlists', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('content_id')->unsigned();
			$table->bigInteger('playlist_id')->unsigned();
			$table->timestamps();

			$table->foreign('content_id')->references('id')->on('ac_contents');
			$table->foreign('playlist_id')->references('id')->on('ac_playlists');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('ac_content_playlists');
	}

}
