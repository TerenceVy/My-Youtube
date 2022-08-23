# DB

## RUN
- ````docker build -t my_api . && docker run -d -p 5432:5432 --name my_api my_api````

# API 

## REQUIRED

- LARAVEL 
- PHP 7.2

## INIT
- ````composer install````

## RUN 
- ````php artisan serve --port=8000````

## UPDATE DB

- ````php artisan migrate````
- ````php artisan passport:install````

## Generate key
- ````php artisan key:generate````