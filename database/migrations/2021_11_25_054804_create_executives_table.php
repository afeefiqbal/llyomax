<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExecutivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('executives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('executive_id');
            $table->unsignedBigInteger('user_id');
            $table->bigInteger('branch_id')->unsigned();
            $table->bigInteger('manager_id')->unsigned();
            $table->string('email');
            $table->string('password');
            $table->string('phone');
            $table->string('username');
            $table->string('place');
            $table->bigInteger('collection_area_id')->nullable();
            $table->string('executive_type');
            $table->string('number_of_executives')->default(0);
            $table->boolean('status')->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('manager_id')->references('id')->on('managers')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('executives');
    }
}
