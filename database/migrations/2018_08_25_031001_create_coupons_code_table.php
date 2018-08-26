<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('coupons_code')) {
            Schema::create('coupons_code', function (Blueprint $table) {
                $table->increments('id');
                $table->string('code')->unique();
                $table->string('value');
                $table->enum('type', ['percent', 'price']);
                $table->enum('status', ['expire', 'limit', 'no limit']);
                $table->integer('limited_at');
                $table->integer('limit_used');
                $table->unsignedBigInteger('created_by');
                $table->unsignedBigInteger('changed_by');
                $table->foreign('created_by')->references('id')->on('users');
                $table->foreign('changed_by')->references('id')->on('users');
                $table->timestamp('expired_at')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('coupons_code');
    }
}
