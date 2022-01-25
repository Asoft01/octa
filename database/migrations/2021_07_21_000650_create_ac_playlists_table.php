<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcPlaylistsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('ac_playlists', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('user_id')->nullable()->unsigned();
			$table->string('uuid')->unique();
			$table->string('poster')->nullable();
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('ac_playlists');
	}

}
