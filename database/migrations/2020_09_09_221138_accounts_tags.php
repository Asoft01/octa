<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AccountsTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_account_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('ac_account_id');
            $table->unsignedBigInteger('ac_tag_id');
        
            $table->foreign('ac_tag_id')
                ->references('id')
                ->on('ac_tags');

            $table->foreign('ac_account_id')
                ->references('id')
                ->on('ac_accounts');

        
            $table->primary(['ac_account_id', 'ac_tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_account_tags');
    }
}
