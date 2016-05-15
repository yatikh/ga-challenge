<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Mockery as M;
use App\Models\Phonenumber;

class PhonenumbersTest extends TestCase
{
    public function tearDown()
    {
        parent::tearDown();

        M::close();
    }

    /**
     * Testing purchasing phone number.
     */
    public function testShouldPurchasePhonenumberAndSaveToDb()
    {
        $country = ['name' => 'France', 'iso' => 'FR'];
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

        $this->withSession(['country' => $country])
            ->post('api/phonenumbers', ['phonenumber' => $phonenumber])
            ->seeJsonEquals(['number' => $phonenumber]);

        $storedNumber = Phonenumber::where(['number' => $phonenumber])->first();

        $this->assertEquals($storedNumber->number, $phonenumber);
    }

    /**
     * Get phonenumbers list from Twilio REST.
     * @depends testShouldPurchasePhonenumberAndSaveToDb
     */
    public function testShouldSeeCurrentPhonenumber()
    {
        $countryIso = 'FR';

        $phonenumber = Phonenumber::where([
            'country_iso' => $countryIso
        ])->first();

        $this->get(sprintf('api/phonenumbers/%s/current', $countryIso))
            ->seeJsonEquals(['number' => $phonenumber->number]);

        // test unavailable country
        $countryIso = 'RU';

        $this->get(sprintf('api/phonenumbers/%s/current', $countryIso))
            ->seeJsonEquals([
                'errors' => ['Phonenumber not found.']
            ]);
    }

    public function testShouldSeeListOfAvailableNumbers()
    {
        $countryCode = 'FR';

        $numberOne = new stdClass;
        $numberOne->phone_number = '+12345';

        $numberTwo = new stdClass;
        $numberTwo->phone_number = '+98765';


        $twilio = M::mock(Services_Twilio::class);
        $account = M::mock('twilio_account');

        $list = M::mock('twilio_list');
        $list->available_phone_numbers = [
            $numberOne, $numberTwo
        ];

        $collection = M::mock('collection')
            ->shouldReceive('getList')
            ->with($countryCode, M::type('string'), M::type('array'))
            ->once()
            ->andReturn($list)
            ->getMock();

        $twilio->account = $account;
        $account->available_phone_numbers = $collection;

        App::instance(Services_Twilio::class, $twilio);

        $this->get(sprintf('api/phonenumbers/%s', $countryCode))
            ->seeJsonEquals([
                '+12345',
                '+98765'
            ]);
    }

}
