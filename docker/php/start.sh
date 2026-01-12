#!/bin/bash

if [ ! -f .env ];
then
    echo ".env file does not exist!"
    cp .env.example .env
else
    echo ".env file already exist!"
fi

chmod 777 -R storage

# composer install

# npm install

# npm run dev

# php /var/www/html/artisan migrate

/usr/bin/supervisord -n -c /etc/supervisor/conf.d/app-queue.conf

exit 0
