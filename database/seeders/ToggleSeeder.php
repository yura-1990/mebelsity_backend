<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ToggleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('toggles')->insert([
            'type' => 'AllOfficeMebels',
            'toggle' => true,
        ]);
        DB::table('toggles')->insert([
            'type' => 'OfficeMebels',
            'toggle' => true,
        ]);
        DB::table('toggles')->insert([
            'type' => 'OfficeLoftMebels',
            'toggle' => true,
        ]);
        DB::table('toggles')->insert([
            'type' => 'SoftMebels',
            'toggle' => true,
        ]);
        DB::table('toggles')->insert([
            'type' => 'HomeMebels',
            'toggle' => true,
        ]);
        DB::table('toggles')->insert([
            'type' => 'KitchenMebels',
            'toggle' => true,
        ]);
    }
}
