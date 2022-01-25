<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('iso');
            $table->string('currency');
            $table->string('name');
            $table->string('symbol');
            $table->index('iso');
        });

        DB::table('ac_currencies')->insert(
            [
                ['iso' => 'USD', 'currency' => 'United States dollar', 'name' => 'USD', 'symbol' => '$'],
                ['iso' => 'CAD', 'currency' => 'Canadian dollar', 'name' => 'CAD', 'symbol' => '$']
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_currencies');
    }
}
