<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'buyer_id' => '1',
            'item_id' => '6',
            'status' => 'pending',
        ];
        DB::table('transactions')->insert($param);

        $param = [
            'buyer_id' => '1',
            'item_id' => '7',
            'status' => 'pending',
        ];
        DB::table('transactions')->insert($param);

        $param = [
            'buyer_id' => '1',
            'item_id' => '8',
            'status' => 'pending',
        ];
        DB::table('transactions')->insert($param);

        $param = [
            'buyer_id' => '1',
            'item_id' => '9',
            'status' => 'pending',
        ];
        DB::table('transactions')->insert($param);

        $param = [
            'buyer_id' => '1',
            'item_id' => '10',
            'status' => 'pending',
        ];
        DB::table('transactions')->insert($param);

        $param = [
            'buyer_id' => '2',
            'item_id' => '1',
            'status' => 'pending',
        ];
        DB::table('transactions')->insert($param);

        $param = [
            'buyer_id' => '2',
            'item_id' => '2',
            'status' => 'pending',
        ];
        DB::table('transactions')->insert($param);

        $param = [
            'buyer_id' => '2',
            'item_id' => '3',
            'status' => 'pending',
        ];
        DB::table('transactions')->insert($param);

        $param = [
            'buyer_id' => '2',
            'item_id' => '4',
            'status' => 'pending',
        ];
        DB::table('transactions')->insert($param);

        $param = [
            'buyer_id' => '2',
            'item_id' => '5',
            'status' => 'pending',
        ];
        DB::table('transactions')->insert($param);
    }
}
