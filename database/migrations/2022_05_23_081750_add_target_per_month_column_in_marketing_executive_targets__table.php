<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTargetPerMonthColumnInMarketingExecutiveTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marketing_executive_targets', function (Blueprint $table) {
            $table->string('target_per_month')->nullable()->after('target_per_day');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('marketing_executive_targets_', function (Blueprint $table) {
            //
        });
    }
}
