## Installation

- Install [Laravel](https://laravel.com/docs/5.8/installation)
- Start a MySQL server, and update .env
```
DB_CONNECTION=mysql
DB_HOST=xxxxxxx
DB_PORT=xxxx
DB_DATABASE=xxxxxxx
DB_USERNAME=xxxxxxx
DB_PASSWORD=xxxxxxx
```
- Create a [Pusher](https://pusher.com/) channel and update .env
```
PUSHER_APP_ID=xxxxx
PUSHER_APP_KEY=xxxxxxxxxxxxxxxx
PUSHER_APP_SECRET=xxxxxxxxxxxxxxxx
PUSHER_APP_CLUSTER=xx
```
- Start the PHP Webserver
```
php artisan serve
```

## Usage
- The idea behind the app is to provide a meeting scheduling tool
- A user is identified on the name that they enter upon loading the page (messages are also saved per user)
- If you try to make a booking for existing book, the bot will tell the user who has booked it, and ask for a different time
- in order to clear all messages and bookings to reset, send "clear db" at any time

## Tests

- Tests can be run by using the phpunit command
