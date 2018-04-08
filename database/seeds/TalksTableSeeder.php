<?php

use Illuminate\Database\Seeder;
// use App\Models\User;
use App\Models\Talk;
class TalksTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$user_ids = ['1','2','3'];
		$faker = app(Faker\Generator::class);

		$talks = factory(Talk::class)->times(100)->make()->each(function ($talk) use ($faker, $user_ids) {
			$talk->user_id = $faker->randomElement($user_ids);
		});

		Talk::insert($talks->toArray());
	}
}
