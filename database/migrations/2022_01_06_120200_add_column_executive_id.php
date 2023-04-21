<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnExecutiveId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amount_transfer_details', function (Blueprint $table) {
            $table->bigInteger('executive_id')->unsigned()->index()->nullable()->after('branch_id');
			$table->foreign('executive_id')->references('id')->on('executives')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amount_transfer_details', function (Blueprint $table) {
            //
        });
    }
}
