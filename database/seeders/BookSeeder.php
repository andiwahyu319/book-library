<?php

namespace Database\Seeders;

use App\Models\Book;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i=0; $i < 50; $i++) { 
            $book = new Book;
            $book->isbn = $faker->randomNumber(6);
            $book->title = $faker->sentence(3);
            $book->year = rand(2010, 2022);
            $book->publisher_id = rand(1, 10);
            $book->author_id = rand(1, 20);
            $book->catalog_id = rand(1, 5);
            $book->qty = rand(1, 20);
            $book->price = rand(5, 20) * 1000;
            
            $book->save();
        }
    }
}
