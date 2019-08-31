<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @return void
     */
    public function run()
    {

        DB::table('relays')->insert([
            'name' => 'Глав.клапан',
            'topic' => 'water/major',
            'state' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('relays')->insert([
            'name' => 'Клапан 1',
            'topic' => 'water/1',
            'state' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('relays')->insert([
            'name' => 'Клапан 2',
            'topic' => 'water/2',
            'state' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('relays')->insert([
            'name' => 'Клапан 3',
            'topic' => 'water/3',
            'state' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

    }

}
