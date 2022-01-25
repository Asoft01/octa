<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPosition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_accounts', function (Blueprint $table) {
            $table->string('position')->nullable()->after('user_id');
            $table->text('bio')->nullable()->after('position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_accounts', function (Blueprint $table) {
            //
        });
    }
}
