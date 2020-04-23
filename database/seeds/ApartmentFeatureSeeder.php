<?php

use Illuminate\Database\Seeder;
use App\Apartment;

class ApartmentFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $apartments = Apartment::all();
      foreach($apartments as $apartment) {
        $features = [];
        for($i = 1; $i <= rand(1,6); $i++) {
        $features[] = $i;
      }
      $apartment->features()->attach($features);
    }
  }
}
