<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('followed_user_id');
            $table->unsignedBigInteger('follower_user_id');
            $table->timestamps();

            $table->foreign('followed_user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table->foreign('follower_user_id')
            ->references('id')
            ->on('users')
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
        Schema::table('follows', function (Blueprint $table) {
            $table->dropForeign(['followed_user_id']);
        });

        Schema::table('follows', function (Blueprint $table) {
            $table->dropForeign(['follower_user_id']);
        });

        Schema::dropIfExists('follows');
    }
}
