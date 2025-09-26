<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Set Up
```shell
sail up
cp .env.example .env
php artisan key:generate
php artisan migrate
composer quality-check
composer test
```

## Notes
- The example XML file had an issue with the `OriginCountry` tag, which was fixed manually.
- The `filepath` setting is not needed and has been moved to configuration (but it is still stored fully in the database).
- Some parts of the application do not fully follow SOLID principles; this was a conscious compromise to keep the app simple for a small project.
- The routes have been prefixed with `/api` to avoid unnecessary complications with Laravel configuration.
- For API tests, use the [postman_collection.json](postman_collection.json).

## Future Improvements
- Error messages are not fully consistent because a custom error handler is not implemented yet.
- Saving files and updating the database could be moved to a queue for asynchronous processing.
- Logging invalid data could be added for debugging (currently, files are not saved if the database record is not created).
- Pagination is not implemented on the invoices list page.
- More tests should be added; currently, only architectural tests exist.
- A cron job could be added to clean up old invoice files that no longer exist in the database.
