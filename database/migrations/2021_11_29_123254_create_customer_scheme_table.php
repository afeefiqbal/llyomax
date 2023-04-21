<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerSchemeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_scheme', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('customer_id')->unsigned();
            $table->bigInteger('branch_id')->unsigned();
            $table->bigInteger('scheme_id')->unsigned();
            $table->bigInteger('executive_id')->unsigned()->nullable();
            $table->string('advance_amount')->default(0)->nullable();
            $table->string('pending_amount')->default(0)->nullable();
            $table->string('total_amount')->default(0)->nullable();
            $table->string('collection_day');
            $table->date('joining_date');
            $table->date('closing_date');
            $table->boolean('status')->default(0); //0 -> pending , 1->active, 2 -> completed , 3->lucky , 4->closed
            $table->foreign('branch_id')->references('id')->on('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('scheme_id')->references('id')->on('schemes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('executive_id')->references('id')->on('executives')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('customer_scheme');
    }
}
