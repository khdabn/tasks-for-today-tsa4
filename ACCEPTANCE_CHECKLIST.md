# TSA4 acceptance checks

Record actual results during local and hosted verification; these are checks to perform, not claimed test evidence.

- [ ] Welcome, Task List, Profile and About load without login.
- [ ] Today page includes only today's active records in Philippine time.
- [ ] Full list is ordered by task_date.
- [ ] Guest GET create/edit and POST/PUT/DELETE management requests are blocked.
- [ ] Breeze login works; incorrect passwords fail; logout ends the session.
- [ ] Create task persists all validated fields.
- [ ] Empty title, invalid status, invalid date and title longer than 255 are rejected.
- [ ] Failed validation redisplays submitted values.
- [ ] Edit pre-fills values and persists changes.
- [ ] Archive sets is_archived=1 without deleting the database row.
- [ ] Archived tasks disappear from public pages and old edit URLs fail.
- [ ] Pending -> done logs a completion email to the configured recipient.
- [ ] Done -> done (including title-only edits) does not generate another email.
- [ ] Creating a task already marked done does not trigger update-transition email.
- [ ] Hosted app uses log mail; logs can be inspected privately in File Manager.
- [ ] composer audit reviewed; php artisan test passed on the actual installation.
- [ ] GitHub contains composer.lock, published Breeze PHP files, migrations and seeder, but no .env/passwords/logs/vendor.
- [ ] Live URL and private instructor login supplied with submission.
