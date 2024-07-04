# Autor

    Vladimir Moreno

## things that you have to do after cloning the respository
    You have to copy the same variables from .env.example
    and create a new file with the name .env
    you have to change these variables :

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE='name of the database that you created'
    DB_USERNAME=
    DB_PASSWORD=

## Run this commands after cloning this repository
    composer install
    php artisan migrate
    php artisan db:seed
    php artisan serve