<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReceivedApartments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('received_apartments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('advert_id');
            $table->string('site');
            $table->string('link');
            $table->integer('city_id');
            $table->integer('district_id');
            $table->integer('street_id');
            $table->string('title');
            $table->timestamp('date');
            $table->enum('type', ['apartment', 'house', 'parcel', 'garage']);
            $table->integer('rooms');
            $table->decimal('total_square', 20, 2);
            $table->decimal('living_square', 20, 2);
            $table->decimal('kitchen_square', 20, 2);
            $table->integer('floor');
            $table->integer('total_floor');
            $table->decimal('price', 20, 2);
            $table->text('description');
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
        Schema::drop('received_apartments');
    }
}
