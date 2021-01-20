## Symfony API

REST API with Symfony 4.4.

### Before You Begin

Create an `.env` file:

    $ cp .env.example .env

Build the Docker containers:

    $ docker-compose up --build

Finally, don't forget to update the `DATABASE_URL` variable in your `.env` file accordingly with the MySQL container IP:

```
DATABASE_URL="mysql://root:@172.18.0.3:3306/database?serverVersion=8.0"
```

### Update the Database Schema

    $ docker exec -itu 1000:1000 symfony_api_php_fpm php bin/console doctrine:migrations:diff
    $ docker exec -itu 1000:1000 symfony_api_php_fpm php bin/console doctrine:migrations:migrate

Alternatively:

    $ docker exec -itu 1000:1000 symfony_api_php_fpm php bin/console doctrine:schema:update --force

### Load the Fixtures

    $ docker exec -itu 1000:1000 symfony_api_php_fpm php bin/console doctrine:fixtures:load

### API Endpoints

Find out your PHP container IP and run the built-in Symfony web server on port `8000`:

    $ docker exec -itu 1000:1000 symfony_api_php_fpm php bin/console server:run 172.18.0.2:8000

Example:

    curl http://172.18.02:8000/api/product/all

Endpoint | HTTP Verb | Description
-------- | --------- | -----------
`api/product/all` | `GET` | All products
