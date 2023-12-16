# Uptime Monitor

Uptime Monitor is a self-hosted web monitoring tool, built with laravel.

## Features

- Monitor your web uptime per minutes (or any time interval)
- Record response time on each web
- Show uptime badges in 3 colors: green for up, yellow for warning, red for down, based on response time
- Send telegram notification when you site down for 5 minutes (based on check periode)

## Why I need this?

- Open-source, modify as you need
- Self-hosted, deploy on your own server
- Store and control your monitoring logs yourself
- Let you know when your websites are down
- For freelancer/agency, increase your client's trust because you monitor their website

## How to Install

### Server Requirements

This application can be installed on local server and online server with these specifications:

1. PHP 8.1 (and meet [Laravel 10.x requirements](https://laravel.com/docs/10.x/deployment#server-requirements)).
2. MySQL or MariaDB Database.
3. SQLite (for automated testing).

### Installation Steps

1. Clone repository: `git clone https://github.com/nafiesl/uptime-monitor.git`
1. `$ cd uptime-monitor`
1. Install PHP dependencies: `$ composer install`
1. Install javscript dependencies: `$ npm install`
1. Copy `.env.example` to `.env`: `$ cp .env.example .env`
1. Generate application key: `$ php artisan key:generate`
1. Create a MySQL or MariaDB database.
1. Configure database and environment variables `.env`.
    ```
    APP_URL=http://localhost:8000
    APP_TIMEZOME="Asia/Jakarta"

    DB_DATABASE=homestead
    DB_USERNAME=homestead
    DB_PASSWORD=secret

    TELEGRAM_NOTIFER_TOKEN=
    ```
1. Run database migration: `$ php artisan migrate --seed`
1. Build assets: `$ npm run build`
1. Run task scheduler: `$ php artisan schedule:work`
1. Start server in a separeted terminal tab: `$ php artisan serve`
1. Open the web app: http://localhost:8000.
1. Login using default user credential:
    - Email: `admin@example.net`
    - Password: `password`
1. Go to **Customer Site** menu.
1. Add some new customer sites (name and URL).
1. After adding customer sites, go to **Dashboard**
1. Click **Start Monitoring** to update the uptime badge per minute.

## Screenshot

TODO

## Lisensi

Uptime Monitor project is an open-sourced software licensed under the [Lisensi MIT](LICENSE).
