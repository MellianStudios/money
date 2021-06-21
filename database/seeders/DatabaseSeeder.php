<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('currencies')->insert([
            ['short' => 'USD', 'name' => 'United States dollar', 'created_at' => $now, 'updated_at' => $now],
            ['short' => 'EUR', 'name' => 'Euro', 'created_at' => $now, 'updated_at' => $now],
            ['short' => 'GBP', 'name' => 'British pound sterling', 'created_at' => $now, 'updated_at' => $now],
            ['short' => 'ETH', 'name' => 'Ethereum', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('currency_pairs')->insert([
            ['base_currency' => 2, 'target_currency' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['base_currency' => 3, 'target_currency' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['base_currency' => 3, 'target_currency' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['base_currency' => 4, 'target_currency' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['base_currency' => 4, 'target_currency' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['base_currency' => 4, 'target_currency' => 3, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
