# trashbin

simple pastebin written in PHP

## setup

set up [silex](https://github.com/igorw/Silex) dependency:

    git clone git://github.com/igorw/Silex.git
    cd Silex
    php compile.php
    cp silex.phar ..
    cd ..
    rm -rf Silex

install and start [mongodb](http://www.mongodb.org).

set up [twig](http://www.twig-project.org) dependency:

    git submodule update --init
    cp example.config.yml config.yml
    # adjust config.yml
    cp example.htaccess .htaccess
    # adjust .htaccess

create a cron job to run garbage_collector.php every now and then.

## contribute

fork, branch, hack, pull request. thanks!

## using

* silex
* mongodb
* twig
* shjs

## license

see LICENSE.
