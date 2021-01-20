## Symfony API

REST API with Symfony 4.4.

## How-to Guide

Create an `.env` file:

    $ cp .env.example .env

Build the Docker containers:

    $ docker-compose up --build

Find the IP of the MySQL Docker container and update the `DATABASE_URL` variable accordingly in your `.env` file.
