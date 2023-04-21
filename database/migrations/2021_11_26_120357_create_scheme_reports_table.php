<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchemeReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheme_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('branch_id')->unsigned();
            $table->bigInteger('customer_id')->unsigned();
            $table->bigInteger('executive_id')->unsigned();
            $table->bigInteger('scheme_id')->unsigned();
            $table->date('date');
            $table->string('advance_amount');
            $table->string('paid_amount');
            $table->string('due_amount');
            $table->boolean('status')->default(0);
            $table->foreign('executive_id')->references('id')->on('executives')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('scheme_id')->references('id')->on('schemes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('scheme_reports');
    }
}
