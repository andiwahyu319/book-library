<?php

namespace Database\Seeders;

use App\Models\Member;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i=0; $i < 20; $i++) { 
            $gender = ["L", "P"];
            $gender = $gender[array_rand($gender)];
            $retVal = ($gender == "L") ? "male" : "female" ;
            $member = new Member;
            $member->name = $faker->name($retVal);
            $member->gender = $gender;
            $member->phone_number = '08' . $faker->randomNumber(2) . $faker->randomNumber(8);
            $member->address = $faker->address;
            $member->email = $faker->email;

            $member->save();
        }
    }
}
