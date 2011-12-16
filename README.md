# trashbin

simple pastebin written in PHP

## setup

set up dependencies:

    wget http://getcomposer.org/composer.phar
    php composer.phar install

install and start [mongodb](http://www.mongodb.org):

    brew install mongo
    mongod

install the mongodb driver for php:

    pecl install mongo
    vi /path/to/php.ini
    # extension=mongo.so

set up [twig](http://twig.sensiolabs.org) dependency:

    git submodule update --init

configure

    cp web/example.htaccess web/.htaccess
    # adjust web/.htaccess

create a cron job to run garbage_collector.php every now and then.

## contribute

fork, branch, hack, pull request. thanks!

## run tests

phpunit is required for the tests.

    phpunit

## using

* silex
* mongodb
* twig
* shjs

## license

see LICENSE.
