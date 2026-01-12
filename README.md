## Project Setup

- composer install
- cp .env.example .env
- Create database in your local phpmyadmin
- Update the DB configurations
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3307
DB_DATABASE=database that you have created
DB_USERNAME=root
DB_PASSWORD=
```
- php artisan key:generate
- php artisan migrate --seed
- npm install
- npm run dev
- php artisan serve
- You can access your application using http://localhost:8085 url


## Project setup with Docker

- Make sure you have WSL installed on your windows machine(https://learn.microsoft.com/en-us/windows/wsl/install)
- For UI experience you can install Docker desktop as well
- Copy docker env file with this command: cp env.docker.example .env.docker.dev
**Note:** Change the PHP Version from latest to specific version

##### Database configuraton in laravel `.env` **must be update**.
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3307
DB_DATABASE=database you want to use
DB_USERNAME=username
DB_PASSWORD=password
```
- docker-compose --env-file .env.docker.dev up --build

**Note:** DB_HOST will be "mysql" to connect with mysql container

- composer install
- php artisan key:generate
- php artisan migrate --seed
- npm install
- npm run dev
- You can access the site by opening in browser http://localhost


##### Check Emails in inbox**.
- Access http://localhost:8027
- In inbox you can access emails sent by application using SMTP as mailhog with below settings
<code>
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1027
</code>



#### packages Used in the application

## secure headers & route function to be used in javascript/jquery with Ziggy
```
    "bepsvpt/secure-headers": "^7.3",
    "tightenco/ziggy": "^1.5",

```

## For Log-viewer
```
"require": {
      ...
        "opcodesio/log-viewer": "1.7.2"
    },

"repositories": [
    {
        "type": "path",
        "url": "Packages/Opcodes/LogViewer/src",
        "options": {
            "symlink": true
        }
    }
]
```
#### application start process
- Login page for admin http://{APP_URL}/admin
- after you have seeded once you can login with the default admin credits provided 
- after successfully login you will be redirected to a static dashboard page
- CMS module will be having [ about us, terms & condition, privacy policy]
- Faq module is added outside which will be used for the frontend faq
- Setting section is having application setting and general setting
    ```
    application setting is having the name of the application which was set in env that you can change and it will be updated. and there is website logo that you can upload from admin panel and update the logo in admin section.
    ```
    ```
    general setting will be having optimize clear, config cache, run migration, run migration fresh and maintenance mode to make the website in maintenance mode simply turn it on from the admin and there is a dedicate error page for site maintenance. you can still access admin panel after making the website in maintenance mode.
    ```
- inside the admin button section we will be having log that you can view from the admin account.
-  
<code>
    - change
    - config\log-viewer.php
    'back_to_system_url' => config('app.url', null),
    to
     'back_to_system_url' => config('app.url', null).'/admin/dashboard',// admin dashboard url
</code>

### after enabling secret manager on the server run the command for the system to not call again and again for load configurations from AWS Secret manager
```
php artisan optimize:clear && php artisan config:cache
```
