## This process is not meant for production
### Skipping or changing order of steps may result in unexpected and/or unsupported errors
#### 1. composer update
#### 2. copy .env.example (linux/mac: cp .env.example .env windows: copy .env.example .env)
#### 3. php artisan key:generate
#### 4. configure pusher credentials according to those sent in email
#### 5. create database and configure credentials in .env
#### 6. php artisan migrate
#### 7. php artisan db:seed
#### 8. php artisan schedule:work (if you want more frequent or at your will timing use php artisan schedule:run)
#### 9. php artisan serve
