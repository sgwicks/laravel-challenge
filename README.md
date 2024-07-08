Created with `composer create-project laravel/laravel` so uses all base settings expected there.

Uses sqlite database. Install if necessary:

```
(For Debian systems)
apt install sqlite php-sqlite3
```

## Tests

`testCreateTicket` is one I'm particularly uncertain of the value of. Discuss

Used an sqlite memory db because that's standard.

## Database

Used an sqlite db because that's what comes out of the box and we only need something light for a tech test. Would not use sqlite in production

Set up the `tickets` table with a reference to `user` rather than storing the user email and name. Also used Laravel's `timestamps` for `created_at` rather than a separate time when ticket was added
