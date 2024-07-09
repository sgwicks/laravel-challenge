## Setup

Built with PHP 8.3.9, Composer 2.7.7, Laravel 11

This project is created with `composer create-project laravel/laravel` with very minimal config changes. That means it uses sqlite database for both development environment and tests. Install if necessary (for Debian systems):

```
apt install sqlite php-sqlite3
```

In order to save space in the zip file, I have deleted the `vendor` folder and the `database.sqlite` file, which means some standard setup is required:

```
composer install
php artisan migrate
php artisan db:seed
```

## Database

Used an sqlite db because that's what comes out of the box and we only need something light for a tech test. I would not expect to use sqlite in a production app.

The seed command will create 11 users - 10 random and 1 custom. It will also create 10 dummy tickets and assign them to random users.

The `tickets` table has been set up with a reference to `user` rather than storing the user email and name. I also rely on Laravel's `timestamps` for `created_at` and `updated_at` rather than creating new columns for these. I imagine that was the expected solution anyway.

I expect that `subject` is a shorter string, while `content` could potentially be quite long, so I used the standard `string` and `text` Laravel helpers for setting those columns as `VARCHAR` and `TEXT` respectively.

Dummy tickets are created using a Faker Factory, using a dummy `sentence` for subject and `paragraph` for content, again to try and mimic what I would expect those content types to look like in a "real" ticket. `status` defaults to false, and `user_id` selects a random id from among all existing users.

## Console Commands

Console commands are run via the scheduler:

```
php artisan schedule:work
```

Generating a ticket simply calls the factory with default dummy data. Processing a ticket makes us of `firstWhere` under the assumption that tickets are always ordered by their creation (since that's the order that the incrementing ids are created). If it's possible for `id` order and `created_at` order to be different then this method would need updating.

## Endpoints

Controllers are split up based on route name, into `TicketController`, `UserController` and `StatsController`. StatsController also makes use of a `StatsService` because its logic is more complex than a single database query. I also make use of a `TicketResource` to ensure that tickets are always returned with the same set of data in the same format.

As mentioned, the tickets and user endpoints are fairly simple database queries, and so are handled in their respective controller methods. I set pagination to 10 because that's the number that jumps to mind when I'm told to paginate something. The spec didn't call for allowing this number to be user controlled, but if it did need to be it would be simple to grab the number from the request query and pass it to the paginate method.

I tried to make the stats service reasonably efficient, since it's potentially handling quite a lot of data. It's probably possible to make it more efficient, I don't know. For `user_most_submitted` specifically, I feel like there's probably a good raw SQL query we could make that would be better than anything I write in Eloquent, but I didn't want to spend too much time on it here.

## Tests

I wrote this app mostly following TDD where I was comfortable with it, creating tests before functionality in most cases. In a few cases I set up functionality first to make sure I was comfortable with what I was writing, and then created the tests afterwards - in those cases, I deliberately broke functionality to ensure that the corresponding test would also fail.

All tests are Feature tests because they all require database access. The testing database uses the standard configuration of an sqlite in-memory database. All that was required to set it up was uncommenting a couple of lines from `phpunit.xml`.

As ever with testing, it's not always obvious when a test is simply re-asserting what we already know to be true. `testCreateTicket` is one I'm particularly uncertain of the value of, for example. But, it was enough to make me confident that tickets were being correctly created and assigned to users, so maybe it served its purpose for how quick it was to create.

## Structure

I followed what I consider to be a fairly standard Laravel/API structure (although what a standard Laravel structure is often seems to differ). I created a single service for the single heavy-lifting logic I needed, and a single Resource for the main response type I wanted to use.

Many of the files were created from CLI, and so in some cases there will be scaffolding that hasn't been fully removed - either usings, code comments or potentially some unused, unneeded files. I've removed or altered the parts I found, but otherwise I'm comfortable that these don't add too much weight or confusion to the app. Again, in a production app I would be more careful about removing unnecessary code and scaffolding than I have been in this tech test.
