<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Mockery as M;

class TwilioTest extends TestCase
{
    public function tearDown()
    {
        parent::tearDown();

        M::close();
    }

    /**
     * Get coutries list from Twilio REST.
     */
    public function testGetCountriesList()
    {
        $response = $this->call('GET', '/twilio/countries');

        $this->assertEquals(200, $response->status());
        $this->assertViewHasAll(['countries']);
    }

    /**
     * Get phonenumbers list from Twilio REST.
     */
    public function testGetPhonenumbers()
    {
        // test available country
        $countryIso = 'DK';

        $this->get('/twilio/phonenumbers/'.$countryIso)
            ->seeJsonStructure([
                'items'
            ]);

        // test unavailable country
        $countryIso = 'RU';

        $this->get('/twilio/phonenumbers/'.$countryIso)
            ->seeJsonEquals([
                'error' => "The requested resource /2010-04-01/Accounts/AC0445772ef66c7b8a1c4716a058109569/AvailablePhoneNumbers/RU/Local.json was not found"
            ]);
    }

    /**
     * Testing purchasing phone number.
     */
    public function testPurchasingNumberAndStoreToDb()
    {
        $country = ['name' => 'Denmark', 'iso' => 'DK'];
        $phonenumber = '+15005550006';

        $twilio = M::mock(Services_Twilio::class);
        $account = M::mock('twilio_account');
        $boughtNumber = M::mock('twilio_number');
        $boughtNumber->phone_number = $phonenumber;
        $incomingNumber = M::mock('twilio_incoming_number')
            ->shouldReceive('create')
            ->with(M::type('array'))
            ->once()
            ->andReturn($boughtNumber)
            ->getMock();

        $twilio->account = $account;
        $account->incoming_phone_numbers = $incomingNumber;

        App::instance(Services_Twilio::class, $twilio);

        $response = $this->withSession(['country' => $country])
            ->call('POST', '/twilio/buy', ['phonenumber' => $phonenumber]);

        $this->assertEquals(302, $response->status());
        $this->assertRedirectedTo('/');
    }

    /**
     * @depends testPurchasingNumberAndStoreToDb
     */
    public function testPurchasingNumberResult()
    {
        $country = ['name' => 'Denmark', 'iso' => 'DK'];
        $phonenumber = '+15005550006';

        $this->withSession(['country' => $country])
            ->visit('/')
            ->see($phonenumber);

        $this->assertViewHasAll(['country', 'phonenumbers']);
    }

}
