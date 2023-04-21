<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameSchemeIdToSchemeAIdInSchemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schemes', function (Blueprint $table) {
            $table->renameColumn('scheme_id', 'scheme_a_id');
            $table->string('scheme_n_id')->nullable();
            $table->longText('details')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scheme_a_id_in_schemes', function (Blueprint $table) {
            //
        });
    }
}
