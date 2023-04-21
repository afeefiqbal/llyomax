<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingExecutiveTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_executive_targets', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('executive_id')->unsigned()->index();
			$table->foreign('executive_id')->references('id')->on('executives')->onDelete('cascade');
            $table->integer('target_per_day')->nullable();
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
        Schema::dropIfExists('marketing_executive_targets');
    }
}
