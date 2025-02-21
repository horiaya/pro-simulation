<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
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
            'item_name' => '腕時計',
            'item_image' => 'Armani+Mens+Clock.jpg',
            'condition_id' => '1',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => '15000',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'item_name' => 'HDD',
            'item_image' => 'HDD+Hard+Disk.jpg',
            'condition_id' => '2',
            'description' => '高速で信頼性の高いハードディスク',
            'price' => '5000',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '2',
            'item_name' => '玉ねぎ３束',
            'item_image' => 'iLoveIMG+d.jpg',
            'condition_id' => '3',
            'description' => '新鮮な玉ねぎ３束セット',
            'price' => '300',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '3',
            'item_name' => '革靴',
            'item_image' => 'Leather+Shoes+Product+photo.jpg',
            'condition_id' => '4',
            'description' => 'クラシックなデザイの革靴',
            'price' => '4000',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '4',
            'item_name' => 'ノートPC',
            'item_image' => 'Living+Room+Laptop.jpg',
            'condition_id' => '1',
            'description' => '高性能なノートパソコン',
            'price' => '45000',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '5',
            'item_name' => 'マイク',
            'item_image' => 'Music+Mic+4632231.jpg',
            'condition_id' => '2',
            'description' => '高音質なレコーディング用マイク',
            'price' => '8000',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '2',
            'item_name' => 'ショルダーバッグ',
            'item_image' => 'Purse+fashion+pocket.jpg',
            'condition_id' => '3',
            'description' => 'おしゃれなショルダーバッグ',
            'price' => '3500',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'item_name' => 'タンブラー',
            'item_image' => 'Tumbler+souvenir.jpg',
            'condition_id' => '4',
            'description' => '使いやすいタンブラー',
            'price' => '500',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '3',
            'item_name' => 'コーヒーミル',
            'item_image' => 'Waitress+With+Coffee+Grinder.jpg',
            'condition_id' => '1',
            'description' => '手動のコーヒーミル',
            'price' => '4000',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'item_name' => 'メイクセット',
            'item_image' => '外出メイクアップセット.jpg',
            'condition_id' => '2',
            'description' => '便利なメイクアップセット',
            'price' => '2500',
        ];
        DB::table('items')->insert($param);
    }
}
