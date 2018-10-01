# Marketplace / Shoping cart site build for SoftUni 10-10-2017 Symfony MVC Course
***

### Functionalities
•	User registration / login and user profiles.

•	User roles (user, administrator, editor)

•	Initial cash for users

•	Product categories and	listing products in categories

•	Add to cart functionality

•	Promotions for certain time interval

o	Promotions on certain products (% discount)

o	Promotions on all products (% discount) 

o	Promotions on certain categories (% discount)

o	If two or more promotions collide on a date period for certain product – the biggest one applies only

•	Visibility only of available products

•	Quantity visibility

• View cart and	checkout the cart

•	Users can sell bought products

•	Editors can add/delete products and product categories

•	Editors can move products between categories

•	Editors can change quantities and reorder products

•	Administrators have full access on products, categories, users and their possessions

•	Managing the cart

•	Users can sell products and put them promotions

•	Users can make comments on products (review)

•	Administrators: ban users

•	Administrators: ban IP’s

## Instalation

#### Prerequisites
  - PHP >= 7.1
  - MySQL
  - Composer

#### Steps

```sh
git clone https://github.com/petkoxray/SymfonyShoppingCart
cd SymfonyShoppingCart
composer install  
php bin/console doctrine:database:create
php bin/console doctrine:schema:create
php bin/console doctrine:fixtures:load
php bin/console server:run 
Open http://localhost:8000 
```

#### How to use
Admin login: shop@petkoxray.eu Password: test  
Edtitor login: editor@petkoxray.eu Passowrd: test  
User login: user@petkoxray.eu Password: test 

#### Demo
Will be added soon...
