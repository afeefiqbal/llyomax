 <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer_id');
            $table->bigInteger('branch_id')->unsigned()->nullable();
            $table->bigInteger('executive_id')->unsigned()->nullable();
            $table->bigInteger('area_id')->unsigned();
            $table->string('name', 127);
            $table->string('parent_name', 127)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('phone_2', 20)->nullable();
            $table->boolean('phone_verified')->default(0);
            $table->string('email', 127)->nullable();
            $table->string('username', 127)->unique();
            $table->string('password')->nullable();
            $table->string('pincode')->nullable();
            $table->string('house_name', 20)->nullable();
            $table->string('building', 20)->nullable();
            $table->string('place', 20)->nullable();
            $table->string('otp', 20)->nullable();
            $table->string('land_mark')->nullable();
            $table->string('city')->nullable();
            $table->integer('referenced_id')->nullable();
            $table->text('address')->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable()->default(0);
            $table->json('images')->nullable();
            $table->string('remarks', 127)->nullable();
            $table->boolean('status')->default(0);  //0 -> pending , 1->started, 2 -> pending , 3->lucky , 4->closed
            $table->foreign('branch_id')->references('id')->on('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('executive_id')->references('id')->on('executives')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('customers');
    }
}
