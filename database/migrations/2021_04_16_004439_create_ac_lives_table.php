<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcLivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_lives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('stream_id')->nullable();
            $table->unsignedBigInteger('game_id')->nullable();
            $table->unsignedSmallInteger('viewer_count')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->text('title')->nullable();
            $table->text('user_login')->nullable();
            $table->text('game_name')->nullable();
            $table->text('thumbnail_offline')->nullable();
            $table->boolean('isStreaming')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_lives');
    }
}
