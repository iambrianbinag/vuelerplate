## Vuelerplate - A dashboard boilerplate

Vuelerplate is a dashboard boilerplate built on Laravel and Vue. It includes authentication, role, permission, activity log. It has a dashboard to see the total number of users, roles, and a real-time activity log.

## Features

1. Dashboard - realtime users, roles count, and activity log.
2. Users - user management.
3. Roles - role management.
4. Permissions - permission management.
5. Logs - system log.

## Installation

Project setup

```bash
$ git clone https://github.com/iambrianbinag/yhk-web.git project
$ cd project
$ composer install
$ npm install
$ cp .env.example .env # THEN EDIT YOUR NEW FILE ACCORDING TO YOUR OWN SETTINGS.
$ php artisan migrate
$ php artisan db:seed
$ php artisan serve
$ php artisan queue:work
$ php artisan websocket:serve
```

## Tests

In order to run tests:

- Install [SQLite](https://www.sqlite.org/index.html).
- run `php artisan test`;

If you want to change the test configuration, edit the `.env.testing` file.




