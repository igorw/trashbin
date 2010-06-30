# trashbin

simple pastebin written in PHP

## setup

    git submodule update --init
    cp example.config.php config.php
    # adjust config.php
    ./doctrine create-db
    ./doctrine migrate

## dev

to generate a new migration use:

     ./doctrine generate-migrations-diff

to generate the models from the schema use:

    ./doctrine generate-models-yaml

to migrate the database use:

to generate the models from the schema use:

    ./doctrine migrate

**note:** always generate the migration first.

## using

* doctrine
* twig
* shjs

## license

see LICENSE.
