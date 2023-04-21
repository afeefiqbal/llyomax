<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BranchTargetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_targets', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('branch_id')->unsigned()->index();
			$table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
			$table->bigInteger('scheme_id')->unsigned()->index();
			$table->foreign('scheme_id')->references('id')->on('schemes')->onDelete('cascade');
            $table->integer('target_per_month')->nullable();
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
        Schema::dropIfExists('branch_targets');
    }
}
