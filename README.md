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

### Telegram Notifier Setup

In order to get notified in Telegram when the customer sites are down, we need to use a Telegram Bot and a Chat ID

1. Create a Telegram Bot ([how to](https://gist.github.com/nafiesl/4ad622f344cd1dc3bb1ecbe468ff9f8a#create-a-telegram-bot-and-get-a-bot-token))
1. Get a Chat ID of the Telegram Bot ([how to](https://gist.github.com/nafiesl/4ad622f344cd1dc3bb1ecbe468ff9f8a#get-chat-id-for-a-private-chat))
1. Update `.env` file, set `TELEGRAM_NOTIFER_TOKEN=your_telegram_bot_token`
1. Set our Chat ID in the Profile Page.
    - Go to User Profile Menu
    - Click Edit Profile
    - Fill the Telegram Chat ID field with `your_chat_id`
    - Click Update Profile
    - Click **Test Telegram Chat** to test the telegram configuration
1. By default, we will have **5 minutes** inteval when the customer sites are down. But we can change the interval per customer sites.
    - Go to Customer Site menu
    - Select one of the customer site and click Edit link
    - Set the Notify User Interval field, between 0 to 60.
    - Set the Notify User Interval field to 0 if you don't want to get notified.

## Screenshot

#### Dashboard
![screen_2023-12-20_004](https://github.com/nafiesl/uptime-monitor/assets/8721551/7b115df3-f2c0-467e-ba1e-b488c0452bc1)
#### Dashboard in mobile device
![screen_2023-12-20_009](https://github.com/nafiesl/uptime-monitor/assets/8721551/11173d6f-437d-49b0-a509-2ddeb7e69b7e)
#### Monitoring graph on customer site detail
![screen_2023-12-20_005](https://github.com/nafiesl/uptime-monitor/assets/8721551/4f412aaf-8848-484b-8ad8-a625898ea187)
#### Monitoring log tab on customer site detail
![screen_2023-12-20_006](https://github.com/nafiesl/uptime-monitor/assets/8721551/2cbbda3c-a13c-4818-8ab7-25ca0ad04b53)
#### User profile menu
![screen_2023-12-20_007](https://github.com/nafiesl/uptime-monitor/assets/8721551/6f352dc4-bfbe-4b1a-8d0e-ee5df4e97ca1)
#### Telegram notification sample
![screen_2023-12-20_008](https://github.com/nafiesl/uptime-monitor/assets/8721551/15ebca99-d920-4764-a567-06e2e1b748df)

## Lisensi

Uptime Monitor project is an open-sourced software licensed under the [Lisensi MIT](LICENSE).
