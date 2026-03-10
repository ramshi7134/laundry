# 🧺 Laundry POS & Cloud — Production Setup Guide

> Laravel 11 · PHP 8.2+ · NativePHP/Electron · Laravel Sanctum · SQLite / MySQL

---

## Table of Contents

1. [Requirements](#1-requirements)
2. [Clone & Install](#2-clone--install)
3. [Environment Configuration](#3-environment-configuration)
4. [Database Setup](#4-database-setup)
5. [Application Key & Storage](#5-application-key--storage)
6. [Running Migrations & Seeders](#6-running-migrations--seeders)
7. [Web Server Setup (Nginx + PHP-FPM)](#7-web-server-setup-nginx--php-fpm)
8. [Queue Worker](#8-queue-worker)
9. [Scheduler (Cron)](#9-scheduler-cron)
10. [Sanctum API Authentication](#10-sanctum-api-authentication)
11. [NativePHP Desktop Build](#11-nativephp-desktop-build)
12. [Production Optimisations](#12-production-optimisations)
13. [Environment Variables Reference](#13-environment-variables-reference)
14. [API Endpoints Quick Reference](#14-api-endpoints-quick-reference)
15. [Deployment Checklist](#15-deployment-checklist)

---

## 1. Requirements

| Requirement               | Version          |
| ------------------------- | ---------------- |
| PHP                       | 8.2 or higher    |
| Composer                  | 2.x              |
| Node.js                   | 18 LTS or higher |
| npm                       | 9+               |
| MySQL / MariaDB _(cloud)_ | 8.0+             |
| SQLite _(local/POS)_      | 3.x              |
| Nginx or Apache           | Latest stable    |

PHP extensions required:

```
pdo, pdo_mysql, pdo_sqlite, mbstring, openssl, tokenizer,
xml, ctype, json, bcmath, fileinfo, gd, zip, curl
```

---

## 2. Clone & Install

```bash
git clone https://github.com/ramshi7134/laundry.git
cd laundry

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies
npm install

# Build frontend assets
npm run build
```

---

## 3. Environment Configuration

```bash
cp .env.example .env
```

Open `.env` and set the following at minimum:

```env
APP_NAME="Laundry POS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Choose: mysql (cloud) or sqlite (local POS)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laundry_prod
DB_USERNAME=laundry_user
DB_PASSWORD=your_strong_password

# For local NativePHP SQLite mode
# DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/database/database.sqlite

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.yourprovider.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=your_mail_password
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 4. Database Setup

### MySQL (Cloud / Production)

```bash
mysql -u root -p
```

```sql
CREATE DATABASE laundry_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'laundry_user'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON laundry_prod.* TO 'laundry_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### SQLite (Local POS / NativePHP)

```bash
touch database/database.sqlite
```

Update `.env`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/laundry/database/database.sqlite
```

---

## 5. Application Key & Storage

```bash
php artisan key:generate

php artisan storage:link
```

Set correct permissions:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 6. Running Migrations & Seeders

```bash
# Run all migrations
php artisan migrate --force

# (Optional) Seed default data — branch, admin user, services, settings
php artisan db:seed --force
```

> ⚠️ Always take a database backup before running `migrate --force` on a live system.

---

## 7. Web Server Setup (Nginx + PHP-FPM)

Create `/etc/nginx/sites-available/laundry`:

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;

    root /var/www/laundry/public;
    index index.php;

    ssl_certificate     /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass   unix:/run/php/php8.2-fpm.sock;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include        fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
ln -s /etc/nginx/sites-available/laundry /etc/nginx/sites-enabled/
nginx -t && systemctl reload nginx
```

### SSL with Let's Encrypt

```bash
apt install certbot python3-certbot-nginx -y
certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

---

## 8. Queue Worker

The sync queue, notifications, and other background jobs need a persistent worker.

### Supervisor (recommended)

Install supervisor:

```bash
apt install supervisor -y
```

Create `/etc/supervisor/conf.d/laundry-worker.conf`:

```ini
[program:laundry-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/laundry/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/laundry/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
supervisorctl reread
supervisorctl update
supervisorctl start laundry-worker:*
```

---

## 9. Scheduler (Cron)

Add to the `www-data` crontab (`crontab -u www-data -e`):

```cron
* * * * * cd /var/www/laundry && php artisan schedule:run >> /dev/null 2>&1
```

---

## 10. Sanctum API Authentication

All API routes (except `POST /api/login`) are protected by Laravel Sanctum.

### Login

```http
POST /api/login
Content-Type: application/json

{
  "phone": "admin@example.com",
  "password": "your_password"
}
```

**Response:**

```json
{
  "access_token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
  "token_type": "Bearer",
  "user": { ... }
}
```

### Using the Token

Include the token in every subsequent request:

```http
Authorization: Bearer 1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### Logout

```http
POST /api/logout
Authorization: Bearer <token>
```

---

## 11. NativePHP Desktop Build

The POS desktop app is built with NativePHP/Electron.

```bash
# Install NativePHP
php artisan native:install

# Development (hot reload)
php artisan native:serve

# Production build
php artisan native:build

# Platform-specific builds
php artisan native:build mac     # macOS .dmg
php artisan native:build win     # Windows .exe
php artisan native:build linux   # Linux .AppImage
```

Built installers are output to `dist/`.

> 📌 The NativePHP app uses `database/nativephp.sqlite` for its local database. The cloud sync endpoints (`/api/sync/push` and `/api/sync/pull`) are used to keep the desktop app in sync with the cloud server.

---

## 12. Production Optimisations

Run these after every deployment:

```bash
# Cache config, routes, views
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Optimise autoloader
composer install --optimize-autoloader --no-dev

# (Optional) Clear all caches before re-caching
php artisan optimize:clear && php artisan optimize
```

### OPcache (php.ini)

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
```

---

## 13. Environment Variables Reference

| Variable                   | Description                          | Example                  |
| -------------------------- | ------------------------------------ | ------------------------ |
| `APP_NAME`                 | Application name                     | `Laundry POS`            |
| `APP_ENV`                  | Environment                          | `production`             |
| `APP_DEBUG`                | Debug mode (must be `false` in prod) | `false`                  |
| `APP_URL`                  | Full URL of the cloud server         | `https://yourdomain.com` |
| `DB_CONNECTION`            | Database driver                      | `mysql` or `sqlite`      |
| `DB_HOST`                  | Database host                        | `127.0.0.1`              |
| `DB_DATABASE`              | Database name                        | `laundry_prod`           |
| `DB_USERNAME`              | Database user                        | `laundry_user`           |
| `DB_PASSWORD`              | Database password                    | `***`                    |
| `SESSION_DRIVER`           | Session storage                      | `database`               |
| `CACHE_STORE`              | Cache driver                         | `database` or `redis`    |
| `QUEUE_CONNECTION`         | Queue driver                         | `database` or `redis`    |
| `MAIL_MAILER`              | Mail driver                          | `smtp`                   |
| `SANCTUM_STATEFUL_DOMAINS` | Allowed Sanctum SPA domains          | `yourdomain.com`         |

---

## 14. API Endpoints Quick Reference

### Auth

| Method | Endpoint      | Description                   |
| ------ | ------------- | ----------------------------- |
| `POST` | `/api/login`  | Login (returns Sanctum token) |
| `POST` | `/api/logout` | Logout                        |
| `GET`  | `/api/user`   | Current user                  |

### Orders (POS)

| Method | Endpoint                    | Description                             |
| ------ | --------------------------- | --------------------------------------- |
| `GET`  | `/api/orders`               | List orders (search, filter, paginate)  |
| `POST` | `/api/orders`               | Create order (split payment, discounts) |
| `GET`  | `/api/orders/{id}`          | Order detail with status log            |
| `POST` | `/api/orders/{id}/status`   | Update order status                     |
| `POST` | `/api/orders/{id}/payments` | Add payment to order                    |

### Customers

| Method   | Endpoint                 | Description                    |
| -------- | ------------------------ | ------------------------------ |
| `GET`    | `/api/customers?search=` | Search by name/phone/email     |
| `POST`   | `/api/customers`         | Create customer                |
| `GET`    | `/api/customers/{id}`    | Customer with wallet & loyalty |
| `PUT`    | `/api/customers/{id}`    | Update customer                |
| `DELETE` | `/api/customers/{id}`    | Delete customer                |

### Services

| Method   | Endpoint                    | Description          |
| -------- | --------------------------- | -------------------- |
| `GET`    | `/api/services?active=true` | List active services |
| `POST`   | `/api/services`             | Create service       |
| `PUT`    | `/api/services/{id}`        | Update service       |
| `DELETE` | `/api/services/{id}`        | Delete service       |

### Reports

| Method | Endpoint                            | Description                         |
| ------ | ----------------------------------- | ----------------------------------- |
| `GET`  | `/api/reports/daily?date=`          | Daily report with expenses & profit |
| `GET`  | `/api/reports/monthly?month=&year=` | Monthly report with daily breakdown |
| `GET`  | `/api/reports/revenue-by-service`   | Revenue per service                 |
| `GET`  | `/api/reports/branch-summary`       | All-time branch totals              |

### Delivery

| Method | Endpoint                      | Description            |
| ------ | ----------------------------- | ---------------------- |
| `GET`  | `/api/deliveries`             | List deliveries        |
| `POST` | `/api/deliveries`             | Assign delivery        |
| `POST` | `/api/deliveries/{id}/status` | Update delivery status |

### Expenses

| Method | Endpoint                   | Description     |
| ------ | -------------------------- | --------------- |
| `GET`  | `/api/expenses`            | List expenses   |
| `POST` | `/api/expenses`            | Log expense     |
| `GET`  | `/api/expenses/categories` | List categories |

### Inventory

| Method | Endpoint                     | Description                 |
| ------ | ---------------------------- | --------------------------- |
| `GET`  | `/api/inventory`             | List inventory              |
| `POST` | `/api/inventory/{id}/adjust` | Add / remove / adjust stock |
| `GET`  | `/api/inventory/low-stock`   | Items below minimum level   |

### Wallet & Loyalty

| Method | Endpoint                              | Description              |
| ------ | ------------------------------------- | ------------------------ |
| `GET`  | `/api/customers/{id}/wallet`          | Wallet balance           |
| `POST` | `/api/customers/{id}/wallet/credit`   | Top-up wallet            |
| `GET`  | `/api/customers/{id}/loyalty/balance` | Loyalty points balance   |
| `POST` | `/api/customers/{id}/loyalty/adjust`  | Manual points adjustment |

### Cloud Sync

| Method | Endpoint                | Description                       |
| ------ | ----------------------- | --------------------------------- |
| `POST` | `/api/sync/push`        | POS pushes local changes to cloud |
| `GET`  | `/api/sync/pull?after=` | POS fetches cloud updates         |
| `GET`  | `/api/sync/status`      | Sync queue health                 |
| `POST` | `/api/sync/retry`       | Retry failed sync items           |

### Settings

| Method | Endpoint              | Description          |
| ------ | --------------------- | -------------------- |
| `GET`  | `/api/settings`       | Get all settings     |
| `POST` | `/api/settings`       | Bulk update settings |
| `GET`  | `/api/settings/{key}` | Get single setting   |

---

## 15. Deployment Checklist

```
□ APP_DEBUG=false in .env
□ APP_ENV=production in .env
□ php artisan key:generate (once, never re-run on existing production)
□ php artisan migrate --force
□ php artisan optimize
□ php artisan storage:link
□ Nginx config tested (nginx -t)
□ SSL certificate installed and auto-renewing
□ Supervisor queue worker running
□ Cron scheduler registered
□ Database backup taken before migration
□ Sanctum STATEFUL_DOMAINS set correctly
□ Storage & bootstrap/cache writable by www-data
□ Logs rotating (logrotate configured)
□ OPcache enabled in php.ini
```

---

## Local Development

```bash
# Start dev server
php artisan serve

# Watch assets
npm run dev

# Run tests
php artisan test

# Clear all caches
php artisan optimize:clear
```

---

_Last updated: March 9, 2026_

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# laundry
