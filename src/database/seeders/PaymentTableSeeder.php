<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'payment' => 'コンビニ払い',
            'slug' => 'konbini',
        ];
        DB::table('payments')->insert($param);

        $param = [
            'payment' => 'カード支払い',
            'slug' => 'card',
        ];
        DB::table('payments')->insert($param);
    }
}
