# trashbin

simple pastebin written in PHP

## setup

    git submodule update --init
    cp example.config.yml config.yml
    # adjust config.yml
    cp example.htaccess .htaccess
    # adjust .htaccess
    ./doctrine create-db
    ./doctrine migrate

## config

the following options can be set in config.yml

### global

**gc_interval**: pastes created before this time will be removed. example value: "24 hours ago"

**footer**: is displayed at the foot of the page.

### doctrine

**dsn**: data source to be used by the doctrine orm, for example: "mysql://root:rootpassword@mydbserver/mydb"

### twig

**debug**: debug mode takes care of recompilation and uses less agressive caching.

## dev

to generate a new migration use:

     ./doctrine generate-migrations-diff

to generate the models from the schema use:

    ./doctrine generate-models-yaml

to migrate the database use:

    ./doctrine migrate

**note:** always generate the migration first.

## using

* doctrine
* twig
* symfony yaml component
* shjs

## license

see LICENSE.
