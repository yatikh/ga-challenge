# ga-challenge

Installation

1. composer install
2. php -r "copy('.env.example', '.env');"
3. php artisan key:generate
4. Add twilio settings to .env (or app/twilio.php)
5. php artisan migrate
6. npm install
7. gulp


Testing

Using next command before testing:
php artisan migrate:refresh
