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

Endpoint | HTTP Verb | Description
-------- | --------- | -----------
`api/category/all` | `GET` | Gets all categories
`api/category/create` | `POST` | Creates a category
`api/category/delete/{id}` | `DELETE` | Deletes a category by id
`api/category/update/{id}` | `PUT` | Updates a category
`api/product/all` | `GET` | Gets all products
`api/product/create` | `POST` | Creates a product
`api/product/featured` | `GET` | Gets the featured products
`api/product/featured/{iso}` | `GET` | Gets the featured products converted to the given currency in ISO format
`api/product/update/{id}` | `PUT` | Updates a product

Find out your PHP container IP and run the built-in Symfony web server on port `8000`:

    $ docker exec -itu 1000:1000 symfony_api_php_fpm php bin/console server:run 172.18.0.2:8000

### Examples

Get all categories:

    $ curl http://172.18.02:8000/api/category/all

Create a category:

    $ curl -X POST -i http://172.18.0.2:8000/api/category/create --data '{
        "name": "Foo",
        "slug": "foo",
        "description": "This is foo"
    }'

Delete a category:

    $ curl -X DELETE http://172.18.0.2:8000/api/category/delete/3

Update a category:

    $ curl -X PUT -i http://172.18.0.2:8000/api/category/update/4 --data '{
        "name": "Foobar",
        "slug": "foobar",
        "description": "This is foo updated"
    }'
