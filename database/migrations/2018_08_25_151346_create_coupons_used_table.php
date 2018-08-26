<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsUsedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('coupons_used')) {
            Schema::create('coupons_used', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('coupon_id');
                $table->unsignedBigInteger('used_by');
                $table->foreign('coupon_id')->references('id')->on('coupons_code');
                $table->foreign('used_by')->references('id')->on('users');
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
        Schema::dropIfExists('coupons_used');
    }
}
