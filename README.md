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
- The example XML file contained an issue with the `OriginCountry` tag, which was manually corrected.
- The `filepath` configuration was found to be unnecessary and has been moved to the configuration settings, as it is not modified.
- The application architecture deviates from SOLID principles in some areas, while in others it implements more structure than necessary for a small application. A balance was chosen.
- For tests API, you can use the posts collection located in the project root.

## Future Improvements
- Not all errors are formatted consistently, as implementing this would require creating a custom error handler.
- Saving files and subsequently updating the database could be moved to a queue for asynchronous processing.
- Logging of invalid data could be added for debugging purposes (currently, files are not saved if the corresponding database record is not created, to maintain consistency).
- Pagination has not yet been implemented on the page listing all invoices.
- Additional tests should be added; currently, only architectural tests have been implemented.
