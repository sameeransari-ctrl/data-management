<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a>
</p>
![Docker Compose](docker-compose-logo.png?raw=true "Docker Compose Logo")



<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


### Steps
##### Step 1
Copy -  `.env.docker.example` file to `.env.docker.dev` OR `.env.docker.prod` 
Modify - Contents of `.env.docker.dev` file.

```dotenv
PROJECT_NAME=
PHP_VERSION=
MYSQL_VERSION=
NODE_VERSION=
MYSQL_PORT=
MYSQL_DATABASE=
MYSQL_USER=
MYSQL_PASSWORD=
MYSQL_ROOT_PASSWORD=
NGINX_PORT=
NGINX_SSL_PORT=
MAILHOG_PORT=
MAILHOG_DASHBOARD_PORT=
REDIS_PORT=
```

**Note:** Change the PHP Version from latest to specific version 

##### Step 2
##### Run laravel application with required services/containers using docker env file

```bash
docker-compose --env-file .env.docker.dev up --build
```
##### Step 3
##### Database configuraton in laravel `.env` **must be update**.
*`Example`*
```dotenv
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laraveldb
DB_USERNAME=docker
DB_PASSWORD=docker
```

**Note:** DB_HOST will be "mysql" to connect with mysql container

##### Step 4
### If you are using `Docker-Desktop` run following commands
```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan config:cache
```
### If you are using node-mix in your application run following commands - `Docker-Desktop` 
```bash
npm run dev
```
### If you are using `cli` run following commands:
```bash
docker exec -it test-app php artisan key:generate
docker exec -it test-app php artisan migrate
docker exec -it test-app php artisan db:seed
docker exec -it test-app php artisan config:cache
```
### If you are using node-mix in your application run following commands
```bash
docker exec -it test-app npm run dev
```

--------

#### Accessing any container 
```bash
docker exec -it <container-name> bash
```
#### *Example:* 
```bash
docker exec -it test-app bash
```

### Services and Default Ports details:

| Services | Default Ports |
|-------------- | -------------- |
| **PHP** | 9000 |
| **Nginx** | 80 |
| **Nginx SSL Port** | 443 |
| **MySQL** | 3306 |
| **MailHog Dashboard** | 8025 |
| **MailHog Server** | 1025 |
| **Redis** | 6379 |

### Docker build by passing PHP Version
```bash
docker build -t php:sample -f /docker/php/Dockerfile --build-arg PHP_VERSION=8.0 .
```
#### You can pass below Arguments while building image

| Arguments | Examples |
| ------ | ------ |
| `PHP_VERSION` | 7.4, 8.0, 8.1 |
| `PORT` | 9000 |

----

#### Other helpful command lines:
##### Remove containers
```bash
docker rm -f <container-name>
```
##### Remove All containers
```bash
docker rm -f $(docker ps -aq)
```

##### Remove image
```bash
docker rmi -f <container-name>
```

##### Run Docker Container with enviornment varilable, ports and volume 
```bash
docker run --rm -e DB_DATABASE=laraveldb -e DB_USERNAME=docker -e DB_PASSWORD=docker -v /var/www/html/projectname:/var/www/html -p 0.0.0.0:9000:9000 --name test-app -itd php:sample
```
---
##### **Note:** For run multiple projects using docker containers you need to use different port configuration in docker environment file. It must be different for other projects.
---

## License
<p align="center">
<a href="https://www.codiant.com/"><img src="https://www.codiant.com/assets/images/codiant-logo.svg" width="400"></a>
</p>