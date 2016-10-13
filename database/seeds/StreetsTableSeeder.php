<?php

use Illuminate\Database\Seeder;

class StreetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(base_path()."/database/seeds/streets.sql"));
    }
}
