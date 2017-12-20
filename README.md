## ShoppingCart build with Symfony 3 for SoftUni MVC Course
####Requiremets:
MySQL, PHP, Composer
####How to install
#####1.Download or git clone repo
#####2.Go into project folder
#####3.Run commands:
3.1.composer install  
3.2 php bin/console doctrine:database:create  
3.3.php bin/console doctrine:schema:create  
_Optional(if you want fake data to be added):_  
3.4 php bin/console doctrine:fixtures:load  
3.5 php bin/console server:run  
#####4.Open http://localhost:8000  
Admin login: shop@petkoxray.eu Password: test   
Edtitor login: editor@petkoxray.eu Passowrd: test  
User login: user@petkoxray.eu Password: test  
