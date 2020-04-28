<?php

use Illuminate\Database\Seeder;
use App\Apartment;
use App\View;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

class ViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i=0; $i < 100; $i++) { 
            $view = new View;
            $view->apartment_id = 1;
            $view->date = $faker->dateTimeThisYear($max = 'now', $timezone = 'Europe/Rome');
            $view->save();
        }
    }
}
