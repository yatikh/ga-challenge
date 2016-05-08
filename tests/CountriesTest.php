<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CountriesTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Show page with list of countries while country wasn't selected.
     */
    public function testShowCountriesList()
    {
        $this->visit('/')
            ->see('Please select your country');
    }

    /**
     * Send the form with country data.
     * Store to session. Redirect to main page.
     */
    public function testSelectCountry()
    {
        $country = [
            'name' => 'Russia',
            'iso' => 'RU'
        ];

        $response = $this->call('POST', '/countries', [
            'country_iso' => $country['iso'],
            'country_name' => $country['name']
        ]);

        $this->assertEquals(302, $response->status());
        $this->assertSessionHas('country', $country);
        $this->assertRedirectedTo('/');
    }

    /**
     * Showing buy number form and country name where
     * country was selected.
     *
     * @dataProvider countryProvider
     */
    public function testShowPurchasingForm($name, $iso)
    {
        $country = [
            'name' => $name,
            'iso' => $iso
        ];

        $this->withSession(['country' => $country])
            ->visit('/')
            ->see("Phone number for a country $name")
            ->see("Unfortunatelly we don't have any numbers in $name yet.")
            ->see("Buy number")
        ;
    }

    /**
     * List of countries for tests.
     *
     * @param array
     */
    public function countryProvider()
    {
        return [
            ['name' => 'Russia', 'iso' => 'RU'],
            ['name' => 'Denmark', 'iso' => 'DK'],
            ['name' => 'United States', 'iso' => 'US'],
        ];
    }
}
