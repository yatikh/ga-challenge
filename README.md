# ga-challenge

Installation

1. composer install
2. php -r "copy('.env.example', '.env');"
3. php artisan key:generate
4. Add twilio settings to .env (or app/twilio.php)
5. php artisan migrate
6. bower install
7. npm install
8. gulp


Testing

Using next command before testing:
php artisan migrate:refresh
