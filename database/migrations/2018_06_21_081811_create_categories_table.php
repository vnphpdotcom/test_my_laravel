<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('ascii');
                $table->string('md5');
                $table->text('desc');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('changed_at')->nullable();
                $table->unsignedBigInteger('created_by');
                $table->unsignedBigInteger('changed_by');
                $table->foreign('created_by')->references('id')->on('users');
                $table->foreign('changed_by')->references('id')->on('users');
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
        //
        Schema::dropIfExists('categories');
    }
}
