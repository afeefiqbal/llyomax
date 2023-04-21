<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesInBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('areas', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('area_district', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('attendances', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('bills', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('branch_cluster', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('branch_scheme', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('branch_targets', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('clusters', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('customer_executives', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('customer_product', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('customer_scheme', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('delivery_boys', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('delivery_order', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('districts', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('executives', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('executive_leaves', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('expenses', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('extra_bonuses', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('lucky_draws', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('managers', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('marketing_executive_targets', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('office_admins', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('order_product', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('rent_allowances', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('schemes', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('salary_incentives', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('sales_commisions', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('scheme_reports', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('sessions', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('staffs', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('transportation_allowances', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('weekly_gifts', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branches', function (Blueprint $table) {
            //
        });
    }
}
