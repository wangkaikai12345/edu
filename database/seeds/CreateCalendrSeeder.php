<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class CreateCalendrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = "INSERT INTO calendar(datelist) SELECT adddate((DATE_FORMAT('2016-1-1', '%Y-%m-%d')), numlist.id ) AS `date`
FROM
(
    SELECT
            n1.i + n10.i * 10 + n100.i * 100 + n1000.i * 1000+ n10000.i * 10000 AS id
        FROM
            num n1
        CROSS JOIN num AS n10
        CROSS JOIN num AS n100
        CROSS JOIN num AS n1000
        CROSS JOIN num AS n10000
    ) AS numlist";

        \Illuminate\Support\Facades\DB::insert($sql);
    }
}
