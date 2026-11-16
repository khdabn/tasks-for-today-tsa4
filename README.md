# Tasks for Today Management System — TSA4

Developed by ANGELO P. BUEN for IT0049 Technical Summative Assessment 4.

Laravel 12 / PHP 8.2+, MySQL, Blade, official Laravel Breeze authentication scaffolding, and completion email notifications. This is a standalone TSA4 version of the earlier TSA3 application. Use a new database so the old application remains available while testing.

## Included behavior

- Public Welcome page shows only today's unarchived tasks in Asia/Manila time.
- Public Task List displays all unarchived tasks, ordered by date.
- Public Profile displays the seeded manager; About identifies the developer.
- Breeze login/logout and password-reset controllers protect management routes. Guests cannot create, update, or archive, including by direct POST/PUT/DELETE requests.
- Create/edit forms validate title, status and date; display errors and preserve old text input.
- Status values are `pending`, `in_progress`, and `done`.
- Archive sets `is_archived = 1`; records are preserved and hidden from public lists. Archived records cannot be edited through their old URL.
- A Mailable and Blade email view are sent through `Mail::to(...)->send(...)` only on an update transition from a non-done status to `done`.
- An already-done task can be renamed without another notification. Reopening and later completing a task generates a new notification.
- Mail defaults to `log`: nothing is delivered to a real inbox. Completion messages appear in `storage/logs/mail.log`.

## Requirements

PHP 8.2+ with normal Laravel extensions, including PDO MySQL, mbstring, openssl, fileinfo, DOM/XML, tokenizer, ctype and session; Composer; MySQL. Enable PDO SQLite for the automated test suite. Node/npm is not required because the supplied Blade views use plain CSS.

## Start on Windows / XAMPP

1. Extract this directory into `C:\xampp\htdocs\tasks-for-today-tsa4`.
2. Start MySQL in XAMPP. Open a terminal in that directory.
3. Run `powershell -NoProfile -ExecutionPolicy Bypass -File .\scripts\setup-local.ps1`.
4. Choose your own password of at least 12 characters when prompted. It is stored only in your ignored local `.env`, not in source control.
5. In phpMyAdmin, create a NEW empty database named `tasks_for_today_tsa4`, collation `utf8mb4_unicode_ci`.
6. Check `.env`: local MySQL defaults are root with no password. Change them if necessary.
7. Run `php artisan migrate --seed`.
8. Run `php artisan test`.
9. Run `php artisan serve`, then open the printed address. If another application is using port 8000, use the printed port and update APP_URL to match.
10. Log in with `buen@example.com` (or ADMIN_EMAIL configured before seeding) and the password you chose.

The setup script installs Composer dependencies and publishes the authentication controllers and LoginRequest directly from the installed official `laravel/breeze` Blade stubs. This is the same PHP scaffolding used by Breeze's installer. The project's custom routes and plain-CSS auth views are retained; Tailwind/Vite assets are not needed. Public registration is closed; management access uses the seeded account.

The source ZIP excludes vendor and does not contain a Composer lock file because dependencies could not be resolved in the build environment. The first successful `composer install` creates composer.lock. Commit that lock and the installed Breeze source files to GitHub. Future machines and production must use `composer install` with that lock, not update.

## Manual setup alternative

```powershell
Copy-Item .env.example .env
composer install
php scripts/install-breeze.php
php artisan key:generate
```

Set ADMIN_PASSWORD and your database values in .env. Create the empty database, then:

```powershell
php artisan config:clear
php artisan migrate --seed
php artisan test
php artisan serve
```

Never run install-breeze.php again after customizing the generated Breeze authentication controllers unless you intentionally want to restore the package versions.

## Database

`users`: id, name (Breeze compatibility), full_name, unique username, unique email, verification timestamp, hashed password, remember token, timestamps.

`tasks`: id, title, status default pending, task_date, is_archived default false, timestamps.

Seeder creates one manager in a fresh database and eight tasks across yesterday, today and tomorrow. Rerunning it does not reset the password or task dates. Stored dates stay fixed; later days can legitimately show an empty Welcome page. Do not modify the date filter to hide that behavior.

## Email configuration and verification

```dotenv
MAIL_MAILER=log
MAIL_LOG_CHANNEL=mail
TASK_NOTIFICATION_RECIPIENT=recipient@example.com
```

The recipient is configurable. With the log driver, this is a test address and no actual message is sent. Run `php artisan config:clear` after editing local configuration.

1. Log in and edit a pending task to Done; save.
2. Open `storage/logs/mail.log` and locate its `Task completed:` subject and task title.
3. Save that same done task again, changing only its title. There must be no additional completion message.
4. Read logs privately in the hosting File Manager when deployed. Never expose logs through a public route.

Task state is committed before synchronous mail is generated. A transport failure is reported as a warning and logged; there is no automatic retry or durable outbox in this assessment. The task is still saved. Concurrent updates are serialized with a row lock, preventing two updates from both observing the same pending state.

## Tests

`php artisan test` uses an in-memory SQLite database and does not erase MySQL data. Tests cover public pages, today's date filter, ordering, archived-record exclusion, authentication of all management routes, form validation and old input, creation, archiving without deletion, actual Breeze login/logout, invalid login, completion mail and prevention of duplicate notifications.

The build environment did not provide PHP, Composer, or permitted dependency downloads; runtime tests have been included but NOT executed there. Run them locally before submission.

## GitHub and deployment

Follow `GITHUB_AND_INFINITYFREE.md`. It covers database export/import, production settings, log-email evidence, production ZIP generation and the final submission checks.

The live application can use your existing `tasksbuen.fwh.is` domain after backup and successful local verification. Confirm the domain still belongs to your hosting account before publishing.

## References

- Laravel 12 authentication: https://laravel.com/docs/12.x/authentication
- Laravel 12 mail: https://laravel.com/docs/12.x/mail
- Official Laravel Breeze package: https://github.com/laravel/breeze
- InfinityFree Laravel deployment: https://forum.infinityfree.com/t/how-to-install-a-laravel-site-on-infinityfree/118578
