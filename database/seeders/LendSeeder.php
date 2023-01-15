<?php

namespace Database\Seeders;

use App\Models\Lend;
use App\Models\LendDetail;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i=0; $i < 10; $i++) {
            $lend = new Lend;
            $lend->member_id = rand(1, 20);
            $lend->date_start = $faker->dateTimeThisMonth('-14 days');
            $lend->date_end = $faker->dateTimeThisMonth('+14 days');
            $lend->book_return = rand(0, 1);
            $lend->save();

        };
        for ($i=0; $i < 20; $i++) {
            $lend_detail = new LendDetail;
            $lend_detail->lend_id = rand(1, 10);
            $lend_detail->book_id = rand(1, 50);
            $lend_detail->qty = rand(1, 5);
            $lend_detail->save();
        }
    }
}
