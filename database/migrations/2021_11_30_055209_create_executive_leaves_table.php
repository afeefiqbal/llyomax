<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExecutiveLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('executive_leaves', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('executive_id')->unsigned();
            $table->bigInteger('branch_id')->unsigned();
            $table->bigInteger('manager_id')->unsigned();
            $table->string('name');
            $table->string('phone');
            $table->date('date');
            $table->string('reason');
            $table->string('status')->default('Pending');
            $table->timestamps();
            $table->foreign('branch_id')->references('id')->on('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('executive_id')->references('id')->on('executives')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('manager_id')->references('id')->on('managers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('executives_leave');
    }
}
