<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<11; $i++){
            DB::table('tasks')->insert(
                [
                    'task' => Str::random(10),
                    'description' => Str::random(30),
                    'status' => 1,
                    'user_id' => rand(1, 5),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            );
        }
    }
}
