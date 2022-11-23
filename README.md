# Laravel Pharmacy
## Project Setup Instructions
- Navigate into project directory
    ```bash
    cd laravel-pharmacy
    ```
- Install composer and npm packages
    ```bash
    composer install
    npm install
    ```
- Set mysql default storage engine in `path/to/bin/mysql/mysql5.7.36/my.ini`
    ```
    ;default-storage-engine=MYISAM
    default-storage-engine=InnoDB
    ```
- Set charset & collation in `config/database.php`
    ```
    return [
    ...
    'connections' => [
        ...
        'mysql' => [
            ...
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
    ```
- Migrate database
    ```bash
    php artisan migrate:fresh
    ```
- Seed database
    ```bash
    php artisan db:seed
    ```
- Generate application key
    ```bash
    php artisan key:generate
    ```
- Put prescription1.png, prescription2.png, prescription3.png files into `storage/app/public/test`

- Create storage symbolic links
    ```bash
    php artisan storage:link
    ```
- Serve application
    ```bash
    php artisan serve
    ```
- Start queue
    ```bash
    php artisan queue:work
    ```
