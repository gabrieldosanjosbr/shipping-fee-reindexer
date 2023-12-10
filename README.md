# Uello Project (Technical Test)
## _Shipping Fee Reindexer_

Users can submit files with range of postalcodes, weight and fee and it will reindex in the database

## Features

- Upload one or more CSV

## Tech

Shipping Fee Mass Readjustment uses a number of open source projects to work properly:

- [Docker] - containerization platform to install and deploy without host dependency.
- [Symfony] - web application framework for fast developing.
- [RabbitMQ] - to queue each file therefore not processing whole file when submitting.
- [Postgresql] - object-relational database to store postcode, weight and its fee.

## Installation

Shipping Fee Mass Readjustment requires [Docker] v20+ and [Docker Compose] v2+ to run.

Install the requirements, clone repository and deploy.

```sh
git clone git@github.com:gabrieldosanjosbr/shipping-fee-mass-readjustment.git testello && cd testello
docker-compose up -d
echo "127.0.0.1 uello.dev" >> /etc/hosts
docker-compose exec php bin/console composer install
docker-compose exec php bin/console doctrine:schema:create
```

## Usage

- Submit files at

```sh
http://uello.dev
```

- Processing files on queue

```sh
  docker-compose exec php bin/console messenger:consume -vv
```




## License

MIT

**Free Software, Hell Yeah!**

[Docker]: <https://www.docker.com/>
[Symfony]: <https://symfony.com/>
[RabbitMQ]: <https://www.rabbitmq.com/>
[Postgresql]: <https://www.postgresql.org/>
[Docker Compose]: <https://docs.docker.com/compose/>
