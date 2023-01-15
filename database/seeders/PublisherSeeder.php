<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
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
            $publisher = new Publisher;
            $publisher->name = $faker->sentence(2);
            $publisher->email = $faker->email;
            $publisher->phone_number = '08' . $faker->randomNumber(2) . $faker->randomNumber(8);
            $publisher->address = $faker->address;

            $publisher->save();
        }
    }
}
