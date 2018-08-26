<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('posts'))
        {
            Schema::create('posts', function (Blueprint $table) {
                $table->increments('id');
                $table->longText('content');
                $table->unsignedBigInteger('user_id');
                $table->timestamp('created_at')->nullable();
                $table->foreign('user_id')->references('id')->on('users');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}