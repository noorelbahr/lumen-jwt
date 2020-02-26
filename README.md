# Implementing JWT Authentication in Laravel Lumen Framework #
The stunningly fast micro-framework by Laravel. More info at https://lumen.laravel.com

## Getting Started
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Set up Lumen project

Open terminal and move to the project root directory
```
cd ~/full/path/to/lumen-jwt
```

Here we assume that our `Apache` and `MySQL` server are running.

---

Copy `.env.example` file to `.env`
```
cp .env.example .env
```

Install dependencies
```
composer install
```

Then, we have to migrate our migration files to create our tables and seed default data for `users`
(in `database/migrations` and `database/seeds`)

Don't forget to create new database named `jwt`, we will use it as our database in this project as mentioned in our `.env` file (`DB_DATABASE=jwt`).
```
php artisan migrate && php artisan db:seed
```
It will creates 1 default user for us.
```
fullname: John Doe
username: johndoe
password: john123
```

##### Run Our Project
Run command below to serve our project locally, we are going to use `port 8080`
```
php -S localhost:8080 -t public
```
Now we can access our project with url http://localhost:8080


## Testing Our API
To test our API, click button bellow : 

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/9a089a056ead81ecd383)

##### or
visit Postman Documenter Link below :

https://documenter.getpostman.com/view/6993569/SzKYMvy6

Then click `Run in Postman` button on top right and Open with `Postman for Mac/Windows`

You can test every single endpoint in the postman collection

---

### Can't see picture from user's picture url ???
##### Create Symlink for Storage Public Folder
Lumen's artisan doesn't support `storage`, that's why we can't use `php artisan storage:link` in Lumen.

So, we have to create it manually with `ln -s source_file symbolic_link`, example:
```
mkdir storage/app/public
ln -s ~/full/path/to/lumen-jwt/storage/app/public ~/full/path/to/lument-jwt/public/storage
```
Replace `~/full/path/to/` with your full path of your project directory.
