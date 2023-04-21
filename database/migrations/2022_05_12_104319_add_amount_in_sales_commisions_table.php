<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmountInSalesCommisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_commisions', function (Blueprint $table) {
            $table->string('amount');
        });
        Schema::table('salary_of_individuals', function (Blueprint $table) {
            $table->string('designation')->after('staff_id');
        });
        Schema::table('extra_bonuses', function (Blueprint $table) {
            $table->dropColumn('amount_selection_of_staff');
            $table->dropColumn('total_amount');
            $table->string('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_commisions', function (Blueprint $table) {
            //
        });
    }
}
