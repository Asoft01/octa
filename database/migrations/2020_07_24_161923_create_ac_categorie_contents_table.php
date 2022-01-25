<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcCategorieContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_category_contents', function (Blueprint $table) {
            $table->unsignedBigInteger('ac_content_id');
            $table->unsignedBigInteger('ac_category_id');
        
            $table->foreign('ac_content_id')
                ->references('id')
                ->on('ac_contents');

            $table->foreign('ac_category_id')
                ->references('id')
                ->on('ac_categories');

        
            $table->primary(['ac_content_id', 'ac_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_categorie_contents');
    }
}
