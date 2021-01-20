## Symfony API

REST API with Symfony 4.4.

## Before You Begin

Create an `.env` file:

    $ cp .env.example .env

Build the Docker containers:

    $ docker-compose up --build

Finally, don't forget to update the `DATABASE_URL` variable in your `.env` file accordingly with the MySQL container IP:

```
DATABASE_URL="mysql://root:@172.18.0.3:3306/database?serverVersion=8.0"
```

## Load the Fixtures

    $ docker exec -itu 1000:1000 symfony_api_php_fpm php bin/console doctrine:fixtures:load
