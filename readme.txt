#this project is created by Hatem Yakoubi founder of DevProd Solution

Features
Administration Dashboard with Gentelella Admin Theme
Responsive Layout
Bootstrap 4
USER/ROLES CRUD and symfony form system
Password reset with link to reset the password
Authentication system
Translation functionality (Easy to set up whatever language you need/use)
Requirements
PHP >= 7.4
Symfony >5.*
MySQL
SETUP
1 - Install all dependencies :

    composer install
2 - Create database using the next command:

    php bin/console doctrine:schema:create
3 - Create scheme using migration command:

    php bin/console doctrine:migrations:migrate
4 - You will need to populate your database using fixtures for login.

Run:

    php bin/console doctrine:fixtures:load
And use the next credentials to login.

Username : "admin"
Password : "admin"
ENJOY