<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchClusterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_cluster', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('branch_id')->unsigned()->index();
			$table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->bigInteger('cluster_id')->unsigned()->index();
			$table->foreign('cluster_id')->references('id')->on('clusters')->onDelete('cascade');
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
        Schema::dropIfExists('branch_cluster');
    }
}
