node -v 20.10.0

https://testtestme.000webhostapp.com/

php artisan make:model ModelEx -mc
php artisan db:masked-dump output.sql
php artisan migrate:rollback --path=/database/migrations/2023_08_18_160434_create_results_table.php