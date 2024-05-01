# Requeriments: 
- npm 
- composer
- node.js 
 
# Installation steps:

1. Clone the project: ```git clone https://github.com/FrancoMuzzio/pizza-catalogue.git``` 
2. Run ```npm install``` and ```composer install```
3. Create the required database (mysql): ```CREATE DATABASE pizzeria;```
4. Create an .env file with the following variables:
```
APP_KEY=
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pizzeria
DB_USERNAME=homestead (or yours)
DB_PASSWORD=secret (or yours)
```
5. Generate the Application key with the command: ```php artisan key:generate```
6. Run the migrations: ```php artisan migrate```
7. Run the seeders with the following commands:
```
php artisan db:seed --class=IngredientSeeder
php artisan db:seed --class=PizzaSeeder
```
8. Build the projecto running: ```npm run build```
9. Try it!
