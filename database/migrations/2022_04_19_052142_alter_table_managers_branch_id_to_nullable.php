<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableManagersBranchIdToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('managers', function (Blueprint $table) {
            $table->string('manager_id')->nullable()->change();
            $table->string('password')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable()->change();
        });
        Schema::table('schemes', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->change();
        });
        Schema::table('scheme_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->change();
        });
        Schema::table('staffs', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->change();
        });
        Schema::table('lucky_draws', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->change();
        });
        Schema::table('customer_scheme', function (Blueprint $table) {
            $table->string('pending_reason')->nullable()->after('pending_amount');
        });
        Schema::table('scheme_reports', function (Blueprint $table) {
            $table->string('pending_reason')->nullable()->after('due_amount');
        });
        Schema::table('executives', function (Blueprint $table) {
            $table->unsignedBigInteger('manager_id')->nullable()->change();
            $table->string('email')->nullable()->change();
        });
        Schema::table('office_admins', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->change();
            $table->string('email')->nullable()->change();
        });
        Schema::table('areas', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->change();
            $table->bigInteger('district_id')->unsigned()->index()->nullable();
			$table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('managers', function (Blueprint $table) {
            //
        });
    }
}
