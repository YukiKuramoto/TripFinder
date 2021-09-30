<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpotCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('spot_comment', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->integer('spot_id');
        $table->integer('user_id');
        $table->string('comment_title');
        $table->string('comment_content');
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
      Schema::dropIfExists('spot-comment');
    }
}
