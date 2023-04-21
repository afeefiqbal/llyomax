<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BranchSchemeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_scheme', function (Blueprint $table) {
            $table->bigInteger('scheme_id')->unsigned()->index()->nullable();
			$table->foreign('scheme_id')->references('id')->on('schemes')->onDelete('cascade');
            $table->bigInteger('branch_id')->unsigned()->index()->nullable();
			$table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
