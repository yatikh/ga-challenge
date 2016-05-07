<?php

namespace App\Providers;

use Services_Twilio;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind('Twilio', function ($app) {
            return new Services_Twilio(
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
        return ['Twilio'];
    }
}
