<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => '1',
            'item_id' => '6',
            'payment_id' => '2',
            'post_code' => '000-000',
            'address' => 'あああああああああ',
            'building_name' => '',
        ];
        DB::table('purchases')->insert($param);

        $param = [
            'user_id' => '1',
            'item_id' => '7',
            'payment_id' => '2',
            'post_code' => '000-000',
            'address' => 'あああああああああ',
            'building_name' => '',
        ];
        DB::table('purchases')->insert($param);

        $param = [
            'user_id' => '1',
            'item_id' => '8',
            'payment_id' => '2',
            'post_code' => '000-000',
            'address' => 'あああああああああ',
            'building_name' => '',
        ];
        DB::table('purchases')->insert($param);

        $param = [
            'user_id' => '1',
            'item_id' => '9',
            'payment_id' => '2',
            'post_code' => '000-000',
            'address' => 'あああああああああ',
            'building_name' => '',
        ];
        DB::table('purchases')->insert($param);

        $param = [
            'user_id' => '1',
            'item_id' => '10',
            'payment_id' => '2',
            'post_code' => '000-000',
            'address' => 'あああああああああ',
            'building_name' => '',
        ];
        DB::table('purchases')->insert($param);

        $param = [
            'user_id' => '2',
            'item_id' => '1',
            'payment_id' => '2',
            'post_code' => '000-000',
            'address' => 'あああああああああ',
            'building_name' => '',
        ];
        DB::table('purchases')->insert($param);

        $param = [
            'user_id' => '2',
            'item_id' => '2',
            'payment_id' => '2',
            'post_code' => '000-000',
            'address' => 'あああああああああ',
            'building_name' => '',
        ];
        DB::table('purchases')->insert($param);

        $param = [
            'user_id' => '2',
            'item_id' => '3',
            'payment_id' => '2',
            'post_code' => '000-000',
            'address' => 'あああああああああ',
            'building_name' => '',
        ];
        DB::table('purchases')->insert($param);

        $param = [
            'user_id' => '2',
            'item_id' => '4',
            'payment_id' => '2',
            'post_code' => '000-000',
            'address' => 'あああああああああ',
            'building_name' => '',
        ];
        DB::table('purchases')->insert($param);

        $param = [
            'user_id' => '2',
            'item_id' => '5',
            'payment_id' => '2',
            'post_code' => '000-000',
            'address' => 'あああああああああ',
            'building_name' => '',
        ];
        DB::table('purchases')->insert($param);
    }
}
