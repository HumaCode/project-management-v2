# System Information

## Environment
- **Project Path**: `c:\laragon\www\project-management-v2`
- **Laravel Version**: 12.0.0
- **PHP Version**: 8.4 (via Docker FrankenPHP)
- **Database**: MariaDB (Docker Container pm_v2_db)
- **Infrastructure**: Dockerized (FrankenPHP + MariaDB)

## Key Files & Locations
- **Models**: `app/Models/`
- **Controllers**: `app/Http/Controllers/`
- **Services**: `app/Services/`
- **Resources**: `app/Http/Resources/`
- **Views**: `resources/views/`
- **CSS**: `resources/css/app.css` (Tailwind 4)
- **JS**: `resources/js/app.js`
- **Routes**: `routes/web.php` & `routes/auth.php`
- **Helpers**: `app/Helpers/`
- **Constants**: `app/Constants/`
- **Broadcasting**: `laravel/reverb` (WebSocket Server on Port 8080)
- **Real-Time Config**: `resources/js/echo.js` & `routes/channels.php`
- **Frontend Assets**: `public/assets/auth/backend/js/project-detail.js` (Modular JS)
- **Pagination Templates**: `resources/views/vendor/pagination/` (e.g., `custom-dashboard.blade.php`)

## Standard Identifiers
- **Primary Keys**: ULID (Universally Unique Lexicographically Sortable Identifier) is used for all core models.
- **Routing**: Primary identifiers (ULID) are preferred for internal dashboard linking to ensure robustness and prevent 404s from slug changes.
- **Slug**: Maintained for SEO/Human-readable URLs but resolved via `orWhere` logic in services to support dual-identifier lookups.
