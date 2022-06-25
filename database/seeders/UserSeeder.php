<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UserSeeder extends Seeder

{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<11; $i++){
            DB::table('users')->insert(
                [
                    'first_name' => 'default'.$i,
                    'last_name' => 'default'.$i,
                    'email' => Str::random(10).$i.'@domain.com',
                    'password' => 'default',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            );
        }
    }
}
