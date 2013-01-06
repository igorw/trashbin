# trashbin

simple pastebin written in PHP

## setup

set up dependencies:

    curl -s https://getcomposer.org/installer | php
    php composer.phar install

install and start [redis](http://redis.io):

    brew install redis
    redis-server

## contribute

fork, branch, hack, pull request. thanks!

## run tests

phpunit is required for the tests.

    composer install --dev
    phpunit

## using

* silex
* redis
* twig
* shjs

## license

see LICENSE.
