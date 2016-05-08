<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Services_Twilio;
use Services_Twilio_Twiml;
use Pricing_Services_Twilio;

class TwilioServiceProvider extends ServiceProvider
{

    protected $defer = true;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // twilio client (calls, sms etc)
        $this->app->bind(Services_Twilio::class, function ($app) {
            return new Services_Twilio(
                $app['config']['twilio']['account_sid'],
                $app['config']['twilio']['auth_token']
            );
        });

        // twiml (dial, say...)
        $this->app->bind(Services_Twilio_Twiml::class, function ($app) {
            return new Services_Twilio_Twiml();
        });

        // pricing API (list of countries)
        $this->app->bind(Pricing_Services_Twilio::class, function ($app) {
            return new Pricing_Services_Twilio(
                $app['config']['twilio']['account_sid'],
                $app['config']['twilio']['auth_token']
            );
        });


    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Services_Twilio::class,
            Services_Twilio_Twiml::class,
            Pricing_Services_Twilio::class,
        ];
    }
}
