<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('ascii');
                $table->string('md5');
                $table->string('extension');
                $table->string('categories');
                $table->string('author');
                $table->double('preprice',20,2);
                $table->double('price',20,2);
                $table->double('score',20,2);
                $table->unsignedInteger('viewed');
                $table->unsignedInteger('pages');
                $table->unsignedInteger('downloaded');
                $table->unsignedInteger('status');
                $table->unsignedInteger('error');
                $table->longText('tags');
                $table->longText('thumbnail');
                $table->longText('description');
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
        Schema::dropIfExists('documents');
    }
}
