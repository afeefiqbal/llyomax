<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmountTransferDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amount_transfer_details', function (Blueprint $table) {
           
            $table->bigIncrements('id');
            $table->bigInteger('branch_id')->unsigned();
            $table->date('date');
            $table->string('transfer_amount');
            $table->time('transfer_time');
            $table->boolean('transfer_type')->default(1);
            $table->foreign('branch_id')->references('id')->on('branches')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('amount_transfer_details');
    }
}
