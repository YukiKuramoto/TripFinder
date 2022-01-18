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
        $table->unsignedBigInteger('spot_id');
        $table->unsignedBigInteger('user_id');
        $table->string('comment_title');
        $table->string('comment_content');
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
      Schema::table('spot_comment', function (Blueprint $table) {
          $table->dropForeign('spot_comment_spot_id_foreign');
      });

      Schema::dropIfExists('spot_comment');
    }
}
