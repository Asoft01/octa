<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcContentTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_content_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('ac_tag_id');
            $table->unsignedBigInteger('ac_content_id');
            $table->timestamps();
        
            $table->foreign('ac_tag_id')
                ->references('id')
                ->on('ac_tags');

            $table->foreign('ac_content_id')
                ->references('id')
                ->on('ac_contents');

        
            $table->primary(['ac_tag_id', 'ac_content_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_content_tags');
    }
}
