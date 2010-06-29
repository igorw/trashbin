# trashbin

simple pastebin written in PHP

## setup

    git submodule update --init
    cp example.config.php config.php
    # adjust config.php
    ./doctrine create-db
    ./doctrine create-tables

## dev

to generate the models from the schema use:

    ./doctrine generate-models-yaml

## using

* doctrine
* twig
* shjs

## license

see LICENSE.
