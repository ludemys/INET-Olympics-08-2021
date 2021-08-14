<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomclassCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roomclass_customer', function (Blueprint $table)
        {
            $table->id();

            $table->unsignedBigInteger('roomclass_id');
            $table->unsignedBigInteger('customer_id');

            $table->foreign('roomclass_id')->references('id')->on('roomclasses');
            $table->foreign('customer_id')->references('id')->on('customers');

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
        Schema::dropIfExists('roomclass_customer');
    }
}
