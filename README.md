ToDoList
========

# Initialise project

## Versions
* PHP 8.0
* Symfony 5.4
* Doctrine 2.11.2
* MySQL 8

## Requirement
* PHP
* Symfony
* Composer
* Docker (optionnal)



## Steps

1. Clone the project repository

Run
````
git clone https://github.com/geoffrey521/projet8-TodoList.git

or via SSH

git clone git@github.com:geoffrey521/projet8-TodoList.git
````

2. Download and install Composer dependencies

At project folder base, run
```
composer install
```

3. Using Database

Change your .env.local file configuration to correspond to your MySQL database

Or

Your can use our docker database by setup your .env.local database like that:
````
DATABASE_URL="mysql://root:secret@127.0.0.1:33061/todolist_db?serverVersion=8&charset=utf8mb4"
````
make sure docker is running, run
````
docker-compose up
````

4. Update database

````
symfony console d:m:m 
````

5. Load data fixtures

````
symfony console d:f:l
````
6. Start server

````
symfony serve
````

7. To associate old orphan tasks to the anonymous user, run:
````
symfony console app:fix-orphan-tasks
````

## Local access:

* Website project :
    * Url: "localhost:8000"
* Phpmyadmin via docker :
    * "localhost:8080"
        * user: root
        * password: secret

### Checkout our documentation in 'docs' folder before any contribution