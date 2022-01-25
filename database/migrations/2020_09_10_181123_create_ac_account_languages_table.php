<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcAccountLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_account_languages', function (Blueprint $table) {
            $table->unsignedBigInteger('ac_account_id');
            $table->unsignedInteger('ac_language_id');
        
            $table->foreign('ac_account_id')
                ->references('id')
                ->on('ac_accounts');

            $table->foreign('ac_language_id')
                ->references('id')
                ->on('ac_languages');
        
            $table->primary(['ac_account_id', 'ac_language_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_account_languages');
    }
}
