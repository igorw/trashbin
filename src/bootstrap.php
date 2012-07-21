<?php

use Igorw\Trashbin\Storage;
use Igorw\Trashbin\Validator;
use Igorw\Trashbin\Parser;

use Predis\Silex\PredisServiceProvider;

use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;

use Symfony\Component\Finder\Finder;

/* prevent direct access */

// @codeCoverageIgnoreStart
if (!$app) {
    exit;
}
// @codeCoverageIgnoreEnd

$app->register(new TwigServiceProvider(), array(
    'twig.path'         => __DIR__.'/../views',
    'twig.options'      => array('cache' => __DIR__.'/../cache/twig', 'debug' => true),
));

$app->register(new UrlGeneratorServiceProvider());

$app->register(new PredisServiceProvider());

$app['catch_exceptions'] = true;

$app['app.languages'] = $app->share(function () {
    $languages = array();
    $finder = new Finder();
    foreach ($finder->name('*.min.js')->in(__DIR__.'/../web/shjs/lang') as $file) {
        if (preg_match('#sh_(.+).min.js#', basename($file), $matches)) {
            $languages[] = $matches[1];
        }
    }

    return $languages;
});

$app['app.storage'] = $app->share(function () use ($app) {
    return new Storage($app['predis']);
});

$app['app.validator'] = $app->share(function () {
    return new Validator();
});

$app['app.parser'] = $app->share(function () use ($app) {
    return new Parser($app['app.languages']);
});
