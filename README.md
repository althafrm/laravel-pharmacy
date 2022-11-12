# Laravel Pharmacy
## Project Setup on Windows
- Install wampserver
- Install composer
- Install nodejs
- Navigate into project directory
    ```bash
    cd laravel-pharmacy
    ```
- [Generate ssh key pair](https://docs.github.com/en/authentication/connecting-to-github-with-ssh/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent) 
- [Add ssh public key to github account](https://docs.github.com/en/authentication/connecting-to-github-with-ssh/adding-a-new-ssh-key-to-your-github-account)
- Install composer and npm packages
    ```bash
    composer install
    npm install
    ```
- Set mysql default storage engine in `C:\wamp64\bin\mysql\mysql5.7.36\my.ini`
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
