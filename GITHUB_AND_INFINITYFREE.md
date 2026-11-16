# GitHub and InfinityFree walkthrough

## 1. Finish local setup first

Use README.md. Confirm `php artisan test` passes, the four public pages work, and a pending-to-done update generates exactly one completion message in storage/logs/mail.log.

Use a fresh database named tasks_for_today_tsa4. The schema adds authentication fields to the old TSA3 user structure. Do not import this schema over an existing TSA3 database or run migrate:fresh on it.

## 2. Publish a separate GitHub repository

In the completed local project directory, after setup installed Breeze:

```powershell
git init
git check-ignore .env
git add .
git status --short
git commit -m "Complete TSA4 task CRUD, Breeze auth, and mail notifications"
git branch -M main
```

Before committing, confirm .env is ignored and that vendor, logs and deployment ZIPs are not staged. composer.lock and generated app/Http/Controllers/Auth and app/Http/Requests/Auth SHOULD be committed.

Create an empty GitHub repository named tasks-for-today-tsa4 under your account. Leave README, .gitignore and license initialization unchecked. Copy its HTTPS URL. Then use `git remote add origin` followed by that URL, and run `git push -u origin main`. Authenticate through your browser when prompted. We will guide these exact steps after local verification.

Add the confirmed live link to README.md after deployment and push that change too.

## 3. Prepare InfinityFree without changing the old database

1. Back up the existing tasksbuen.fwh.is files and export its current database before replacing the hosted application. Alternatively add a new subdomain such as tasksbuen-tsa4 if available.
2. Create a NEW InfinityFree MySQL database, suffix taskstsa4. Use the full prefixed database name shown by the dashboard, not the example suffix alone.
3. Export your LOCAL tasks_for_today_tsa4 database through phpMyAdmin: Quick, SQL.
4. Open phpMyAdmin beside the NEW hosted database and import the SQL. Verify users and tasks appear. Passwords are hashed in the export.
5. Do not put database SQL exports inside htdocs; import them through phpMyAdmin only.

## 4. Create the production upload ZIP

From the local project root, run:

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\scripts\prepare-deploy.ps1
```

This installs locked production dependencies in deploy-output/htdocs and creates deploy-output/TSA4-InfinityFree.zip. It does not change local vendor or your working database. It excludes .env, session records and logs. The ZIP includes root .htaccess and all runtime dependencies. Upload it through File Manager -> Upload & Unzip inside the chosen domain's htdocs folder, replacing the old application only after backup. The archive must open directly to app, bootstrap, config, public, etc.

If InfinityFree rejects a large ZIP, split vendor into smaller ZIPs or upload extracted folders with an FTP client. Do not upload a giant source ZIP into public and leave it there. Remove any leftover archive after extraction.

## 5. Production .env

Use deployment/.env.infinityfree.example as a reference, not as a ready credential file. Edit the hosted .env privately:

- APP_ENV=production and APP_DEBUG=false.
- APP_URL must exactly match your live domain, including HTTPS if enabled. Do not use localhost.
- APP_KEY: use the key generated for this new application during setup. Do not leave it blank or publish it in GitHub.
- DB_HOST and DB_USERNAME: copy the CURRENT dashboard values. Earlier values were sql113.infinityfree.com and if0_43008122, but verify them.
- DB_DATABASE: full name of the NEW taskstsa4 database you created.
- DB_PASSWORD: your hosting database password, quoted if needed.
- SESSION_DRIVER=file, CACHE_STORE=file and QUEUE_CONNECTION=sync.
- SESSION_SECURE_COOKIE=true for HTTPS; false only if using HTTP.
- MAIL_MAILER=log and MAIL_LOG_CHANNEL=mail.
- TASK_NOTIFICATION_RECIPIENT: a valid test email address such as recipient@example.com. No inbox receives mail in log mode.
- APP_TIMEZONE=Asia/Manila.

Do not overwrite production .env with the local database settings. No SMTP credentials, cron jobs, queue workers or storage symlinks are needed. This assessment has no uploads.

The production ZIP sets htdocs/.htaccess to send every request through public. Keep public/.htaccess too. Private logs and .env must never be web-accessible.

## 6. Verify the hosted result

As a guest, open /, /tasks, /profile and /about. All must load. Trying /tasks/create or an edit URL must redirect to /login.

Log in using the manager account imported from your local database. Create a task with a title, status and date. Try an invalid submission and confirm errors and old input. Edit a pending task to done, then read storage/logs/mail.log PRIVATELY through File Manager. Save the already-done task again and verify no second message. Archive a test task: it disappears from both lists but phpMyAdmin still shows it with is_archived=1. Log out and verify management is blocked again.

For today's-date evidence on a later day, create a task dated TODAY using the authenticated form. Old seeded dates do not roll forward automatically.

Keep screenshots of public and authenticated pages, validation, the archive flag and the mail log evidence. Hide passwords, keys and session data from screenshots.

## 7. Submission

Submit the GitHub URL and the confirmed live application URL. The repository includes migrations and seeders, satisfying the database-source alternative without a public SQL dump. Give the instructor the manager login through your school's private submission channel; never put the password in README or a public repository.

## Troubleshooting

- 500 after upload: inspect storage/logs/laravel.log in File Manager. Keep APP_DEBUG=false. Verify .env key, MySQL details and that vendor exists.
- Old routes/views: delete only generated PHP cache files inside bootstrap/cache and storage/framework/views in the hosting copy; retain directories. Do not delete sessions, user data or source files.
- Missing class: compare the installed local vendor folder against hosted files; retry incomplete transfers. InfinityFree can reject oversized PHP files. Inspect vendor/composer/autoload_classmap.php size before changing autoloader optimization.
- Missing login controller: run php scripts/install-breeze.php locally with dev dependencies installed, commit the generated PHP files, and rebuild the deployment ZIP.
- Local connection refused: start php artisan serve and keep that terminal open.
