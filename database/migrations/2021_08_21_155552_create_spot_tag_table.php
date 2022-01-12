<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpotTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spot_tag', function (Blueprint $table) {
            $table->integer('tag_id');
            $table->unsignedBigInteger('spot_id');
            $table->timestamps();

            $table->foreign('spot_id')
                  ->references('id')
                  ->on('spots')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spot_tag');
    }
}
