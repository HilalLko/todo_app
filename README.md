## Installation

``` bash
# clone the repo
$ git clone https://github.com/am-Hilal/cypress_task.git my-project

# go into app's directory
$ cd my-project

# install app's dependencies
$ composer install

# install app's dependencies
$ npm install

```
Copy file ".env.example", and change its name to ".env".
Then in file ".env" complete this database configuration:
* DB_CONNECTION=mysql
* DB_HOST=127.0.0.1
* DB_PORT=3306
* DB_DATABASE=laravel
* DB_USERNAME=root
* DB_PASSWORD=


### Set APP_URL

> If your project url looks like: example.com/sub-folder 
Then go to `my-project/.env`
And modify this line:

* APP_URL = 

To make it look like this:

* APP_URL = http://example.com/sub-folder


### Next step

``` bash
# in your app directory
# generate laravel APP_KEY
$ php artisan key:generate

# run database migration and seed
$ php artisan migrate:refresh --seed

# generate mixing
$ npm run dev

# and repeat generate mixing
$ npm run dev
```

### Set QUEUE_CONNECTION
``` bash
# change Queue Connection/Drviver from Sync to
QUEUE_CONNECTION=database
```
## Usage

``` bash
# start local server
$ php artisan serve

# Or start local server with port number
$ php artisan serve --port=4041

# Run queue worker
$ php artisan queue:work

# test
$ php vendor/bin/phpunit
```

Open your browser with address: [localhost:8000/sitemaster](localhost:8000/sitemaster)  
Click "Login" on sidebar menu and log in with credentials:

* E-mail: _hilal_rf+admin@hotmail.com_
* Password: _Admin@123$_

The above user is an Admin role user.

``` bash
#Link for postman collection
https://www.getpostman.com/collections/efb31b7182d3d1ac5e53
#Ends
```