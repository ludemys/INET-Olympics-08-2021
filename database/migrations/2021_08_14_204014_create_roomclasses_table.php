<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomclassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roomclasses', function (Blueprint $table)
        {
            $table->id();

            $table->string('number', 4);
            $table->text('description');
            $table->float('price');

            $table->unsignedBigInteger('days_combination_id');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('professor_id');


            $table->foreign('days_combination_id')->references('id')->on('days_combinations');
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->foreign('professor_id')->references('id')->on('professors');

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
        Schema::dropIfExists('roomclasses');
    }
}
