<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'テストA',
            'email' => 'aaa@aaa.com',
            'password' => Hash::make('aaaa1234'),
            'remember_token' => Str::random(10),
            'email_verified_at' => Carbon::now(),
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => 'テストB',
            'email' => 'bbb@bbb.com',
            'password' => Hash::make('bbbb1234'),
            'remember_token' => Str::random(10),
            'email_verified_at' => Carbon::now(),
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => 'テストC',
            'email' => 'ccc@ccc.com',
            'password' => Hash::make('cccc1234'),
            'remember_token' => Str::random(10),
            'email_verified_at' => Carbon::now(),
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => 'テストD',
            'email' => 'ddd@ddd.com',
            'password' => Hash::make('dddd1234'),
            'remember_token' => Str::random(10),
            'email_verified_at' => Carbon::now(),
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => 'テストE',
            'email' => 'eee@eee.com',
            'password' => Hash::make('eeee1234'),
            'remember_token' => Str::random(10),
            'email_verified_at' => Carbon::now(),
        ];
        DB::table('users')->insert($param);
    }
}
