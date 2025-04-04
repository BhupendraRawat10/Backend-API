this laravel project i am uesing 11.22 version with php 8.2
 1 - composer create-project --prefer-dist laravel/laravel insurance-api "11.22"
 2 - Configure the Database 
 3 - composer require laravel/sanctum 
     php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
    php artisan migrate
4 -  no kernal so sanctum update in (bootstrap/app.php)
5 - table create
    [ php artisan make:migration create_users_table
      php artisan make:migration create_roles_table
      php artisan make:migration create_user_role_table
      php artisan make:migration create_policies_table ]
6 -  then controller create 
7 -   php artisan make:seeder RolesTableSeeder 


api - all api create api.php

