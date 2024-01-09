node -v 20.10.0

https://testtestme.000webhostapp.com/

[//]: # (SQLITE_MAX_VARIABLE_NUMBER 166)

php artisan optimize:clear
php artisan cache:clear

php artisan make:resource UserResource
php artisan make:resource UserCollection

php artisan make:model ModelEx -mc
php artisan db:masked-dump output.sql
php artisan migrate:refresh --path=/database/migrations/2023_08_18_141505_create_favorites_table.php
php artisan migrate:rollback --path=/database/migrations/2023_08_18_160434_create_results_table.php