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

## Tests
