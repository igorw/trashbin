# trashbin

simple pastebin written in PHP

## setup

set up [silex](https://github.com/fabpot/Silex) dependency:

    git clone git://github.com/igorw/Silex.git
    cd Silex
    ./compile
    cp silex.phar ..
    cd ..
    rm -rf Silex

install and start [mongodb](http://www.mongodb.org):

    brew install mongo
    mongod

install the mongodb driver for php:

    pecl install mongo
    vi /path/to/php.ini
    # extension=mongo.so

set up [twig](http://www.twig-project.org) dependency:

    git submodule update --init

configure

    cp example.htaccess .htaccess
    # adjust .htaccess

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
