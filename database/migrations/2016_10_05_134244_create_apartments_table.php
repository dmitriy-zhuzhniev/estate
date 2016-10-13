<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartments', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['apartment', 'house', 'parcel', 'garage']);
            $table->string('title');
            $table->string('realty_id');
            $table->string('customer');
            $table->string('owner');
            $table->string('agreement_id');
            $table->string('realty_goal');
            $table->integer('region_id')->index();
            $table->integer('city_id')->index();
            $table->integer('district_id')->index();
            $table->integer('street_id')->index();
            $table->string('house_number');
            $table->integer('apartment_number');
            $table->decimal('square', 20, 2);
            $table->integer('floor');
            $table->integer('total_floor');
            $table->integer('rooms');
            $table->integer('user_id');
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
        Schema::drop('apartments');
    }
}
