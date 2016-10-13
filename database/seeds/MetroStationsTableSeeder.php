<?php

use Illuminate\Database\Seeder;

class MetroStationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(base_path()."/database/seeds/metro_stations.sql"));
    }
}
