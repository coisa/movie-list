# CoiSA Movie List

This project provide a web application to list and details movies form [TMDb](https://www.themoviedb.org).

## Prerequisites

This implementation rely on the `docker` and `docker-compose` binaries.

Make sure that you have already installed this dependencies before execute the described commands of this file:

- [Docker](https://docs.docker.com/install/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Project Architecture

The project defines a very simple initial architecture (it will change for a more robust one in future implementations).
It will requests informations from the TMDb API and cache the response into a local cache storage.
Every time the same information was be requested it will return the response from the cache storage (by default store for a day).

This implementation was made using PHP, exclusively relying on standards suggested by the [PHP-Fig](https://www.php-fig.org) also known as [PSRs](https://www.php-fig.org/psr/).
In that way any dependency used to provide implementations of PSRs can be replaced anytime, anywhere in the code.

All classes implemented here was made using the principle of dependency injection and inversion of control (using the [PSR-11](https://www.php-fig.org/psr/psr-11/)),
so any implementation can be easily intercepted or replaced via container dependency injection.

### Chosen libraries & dependencies

- [PSR-11 Container](https://www.php-fig.org/psr/psr-11/) are being provided by [Zend ServiceManger](https://docs.zendframework.com/zend-servicemanager/) defined into `config/container.php` file.
- The `ConfigProvider` pattern suggested by the Zend Framework was applied for merging configs through [Zend Config Aggregator](https://docs.zendframework.com/zend-config-aggregator/) defined into `config/config.php` file.
- The [HTTP RequestHandler](https://www.php-fig.org/psr/psr-15/) layer are also being provided by a [Zend](https://docs.zendframework.com/zend-expressive/) implementation library.
- [HTTP Request Client](https://www.php-fig.org/psr/psr-18/) are being provided through [Symfony HTTP Client Component](https://symfony.com/components/HttpClient).
- The cache layer are beeing provided through [PSR-16](https://www.php-fig.org/psr/psr-16/) via Filesystem Adapter of [PHP-Cache](http://php-cache.com/en/latest/) implementation.
- Log are being managed by [Monolog](https://github.com/Seldaek/monolog) through my very own [Monolog Factories](https://github.com/coisa/monolog) library.

## Build & Deploy

Follow the steps described below:

- Copy the `.env.example` to a `.env` file;
- Add the TMDb API Key into `TMDB_API_KEY` of yours `.env` file;
- Run in a terminal, inside application path directory:
    ```sh
    $ docker-compose -f docker-compose.yml up --build -d webapp
    ```
- Open your browser into [http://localhost](http://localhost);

## Development

For development mode, use the steps described below:

- Copy the `.env.example` to a `.env` file;
- Add the TMDb API Key into `TMDB_API_KEY` of yours `.env` file;
- Set `CONFIG_CACHE_ENABLED` of yours `.env` file to `0`;
- Run in a terminal, inside application path directory:
    ```sh
    $ docker-compose run --rm composer install
    $ docker-compose up -d webapp
    ```
- Open your browser into [http://localhost](http://localhost);

## Logs

Monitoring the logs are as simple as run the following command:

```bash
$ docker-compose logs -f webapp
```

### Running tests

This project provide phpunit test cases that can be run with the following command:

```sh
$ docker-compose run --rm phpunit
```

## TODO - Next steps

- Change to use Redis as a cache provider;
- Add a local database store, to avoid ask every time for information to TMDb API, and also act as a fallback in fail situations;
- Schedule async updates of upcoming movies, making the frontend only consume the local store database;
- Decouple the frontend from application code. To do so it would be needed first:
    - Change endpoints to API with JSON response bodies;
    - Add some authorization logic (such as OAuth2);
    - Isolate frontend that will only interact with the backend via async requests (could be also separated into another repository);
- Isolate TMDb API communication logic into a separate composer library;

## Credits

- [Felipe Sayão Lobato Abreu](https://github.com/coisa)
