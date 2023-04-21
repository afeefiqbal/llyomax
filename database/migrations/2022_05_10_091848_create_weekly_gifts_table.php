<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeeklyGiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekly_gifts', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('amount');
            $table->bigInteger('scheme_id')->unsigned()->index()->nullable();
            $table->foreign('scheme_id')->references('id')->on('schemes')->onDelete('cascade');
            $table->string('given_by');
            $table->bigInteger('branch_id')->unsigned()->index()->nullable();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->string('gift_items')->nullable();
            $table->string('week');
            $table->bigInteger('customer_id')->unsigned()->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
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
        Schema::dropIfExists('weekly_gifts');
    }
}
