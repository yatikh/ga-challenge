<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Mockery as M;

class CountriesTest extends TestCase
{
    public function tearDown()
    {
        parent::tearDown();

        M::close();
    }

    /**
     * Get countries list.
     */
    public function testShouldSeeCountryList()
    {
        $twilio = M::mock(Pricing_Services_Twilio::class);

        $countryFinland = new stdClass;
        $countryFinland->country = 'Finland';
        $countryFinland->iso_country = 'FI';

        $countryFrance = new stdClass;
        $countryFrance->country = 'France';
        $countryFrance->iso_country = 'FR';

        $twilio->phoneNumberCountries = [
            $countryFinland, $countryFrance
        ];

        App::instance(Pricing_Services_Twilio::class, $twilio);

        $this->get('/api/countries')
            ->seeJsonEquals([
                [
                    [
                        'name' => 'Finland',
                        'iso' => 'FI'
                    ],
                ],
                [
                    [
                        'name' => 'France',
                        'iso' => 'FR'
                    ]
                ]
            ]);
    }

    public function testShouldKeepCountryInSession()
    {
        $country = [
            'name' => 'France',
            'iso' => 'FR'
        ];

        $this->post('/api/countries', $country)
            ->seeJsonEquals([
                'key' => 'FR'
            ]);

        $this->assertSessionHas('country', $country);
    }

    /**
     * @depends testShouldKeepCountryInSession
     */
    public function testShouldRetrieveCurrentCountryFromSession()
    {
        $country = [
            'name' => 'France',
            'iso' => 'FR'
        ];

        $this->withSession(['country' => $country])
            ->get('/api/countries/current')
            ->seeJsonEquals($country);
    }
}
