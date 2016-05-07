<?php

use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getFixtureData() as $countryData) {
            DB::table('countries')->insert($countryData);
        }
    }

    protected function getFixtureName()
    {
        return 'countries.php';
    }

    protected function getFixtureData()
    {
        return include implode(
            DIRECTORY_SEPARATOR,
            [__DIR__, '..', 'fixtures', $this->getFixtureName()]
        );
    }
}
