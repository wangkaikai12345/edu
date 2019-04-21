<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class CreateNumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            \Illuminate\Support\Facades\DB::table('num')->insert(compact('i'));
        }
    }
}
