<?php

namespace Igorw\Trashbin;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Finder\Finder;

class Provider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['app.languages'] = $app->share(function () {
            $languages = array();
            $finder = new Finder();
            foreach ($finder->name('*.min.js')->in(__DIR__.'/../../../web/shjs/lang') as $file) {
                if (preg_match('#sh_(.+).min.js#', basename($file), $matches)) {
                    $languages[] = $matches[1];
                }
            }

            return $languages;
        });

        $app['app.storage'] = $app->share(function ($app) {
            return new RedisStorage($app['predis']);
        });

        $app['app.validator'] = $app->share(function () {
            return new Validator();
        });

        $app['app.parser'] = $app->share(function ($app) {
            return new Parser($app['app.languages']);
        });
    }

    public function boot(Application $app)
    {
    }
}
