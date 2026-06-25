# App Market

An e-commerce application built with Laravel 12 + Vue 3 SPA. Laravel serves a single Blade view; all routing is handled client-side.

## Stack

| Layer | Technologies |
|---|---|
| Backend | PHP 8.2+, Laravel 12, Laravel Sanctum, Spatie Permission / Medialibrary / Translatable / Activity Log |
| Frontend | Vue 3, Vite, TypeScript, Pinia, Vue Router, Tailwind CSS 4, Reka UI, TanStack Table |
| Database | MySQL 8 |

## Features

- Product catalog with categories, brands, and variants (size, color, etc.)
- Catalog filtering and search
- Shopping cart (session-based, works without authentication)
- Order placement
- Customer account — order history
- Multilingual content (spatie/laravel-translatable)
- Authentication: email/password + OAuth (Laravel Socialite)
- Admin panel:
  - Product management (create, edit, soft delete, restore)
  - Product image upload and reordering
  - Category management
  - Order management
  - Stock monitoring by location
  - Error log

## Getting Started

### Requirements

- PHP 8.2+
- Node.js 20+
- MySQL 8

### Installation

```bash
git clone https://github.com/maksanikienko/app-market.git
cd app-market

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Configure `.env` with your database credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) and other environment variables.

```bash
php artisan migrate --seed
php artisan storage:link
```

### Running

```bash
npm run dev
```

## Deployment

See [DEPLOY.md](DEPLOY.md) for the full server deployment guide including backups and caching.

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate
php artisan config:cache && php artisan route:cache && php artisan view:cache
php artisan storage:link
npm run build
```

## Testing

```bash
php artisan test
php artisan test --filter ClassName
```

## License

[MIT](LICENSE)
