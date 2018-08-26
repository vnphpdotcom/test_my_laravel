<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('purchase')) {
            Schema::create('purchase', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('transaction_code');
                $table->string('method');
                $table->integer('status');
                $table->double('total_amount',20,2);
                $table->unsignedBigInteger('document');
                $table->unsignedBigInteger('requested_by');
                $table->unsignedBigInteger('accepted_by');
                $table->foreign('document')->references('id')->on('documents');
                $table->foreign('requested_by')->references('id')->on('users');
                $table->foreign('accepted_by')->references('id')->on('users');
                $table->timestamp('requested_at')->nullable();
                $table->timestamp('accepted_at')->nullable();
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
        Schema::dropIfExists('purchase');
    }
}
