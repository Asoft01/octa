<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('domain_id');
            $table->unsignedInteger('language_id');
            $table->unsignedInteger('product_family_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('isLive');
            $table->boolean('isPublic');
            $table->decimal('quantity', 8, 2);
            $table->unsignedInteger('unit_id');
            $table->decimal('price', 8, 2);
            $table->timestamps();

            $table->foreign('domain_id')->references('id')->on('ac_domains');
            $table->foreign('language_id')->references('id')->on('ac_languages');
            $table->foreign('product_family_id')->references('id')->on('ac_product_families');
            $table->foreign('unit_id')->references('id')->on('ac_units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_products');
    }
}
