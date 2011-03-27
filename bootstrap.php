<?php
/*
 * This file is part of trashbin.
 *
 * (c) 2010 Igor Wiedler
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Silex\Extension\TwigExtension;
use Silex\Extension\UrlGeneratorExtension;

use Symfony\Component\Finder\Finder;

/* prevent direct access */
if (!$app) {
    exit;
}

$app->register(new TwigExtension(), array(
    'twig.path'         => __DIR__.'/views',
    'twig.class_path'   => __DIR__.'/vendor/twig/lib',
    'twig.options'      => array('cache' => __DIR__.'/cache/twig', 'debug' => true),
));

$app->register(new UrlGeneratorExtension());

$app['autoloader']->registerNamespace('Symfony', __DIR__.'/vendor/symfony/src');

$app['app.languages'] = function() {
    $languages = array();
    $finder = new Finder();
    foreach ($finder->name('*.min.js')->in(__DIR__.'/vendor/shjs/lang') as $file) {
        if (preg_match('#sh_(.+).min.js#', basename($file), $matches)) {
            $languages[] = $matches[1];
        }
    }
    return $languages;
};

$app['app.gc_interval'] = strtotime('24 hours ago');
$app['footer'] = 'Hosted by <a href="https://affiliates.nexcess.net/idevaffiliate.php?id=1184">Nexcess.net</a>';

$app['app.garbage_collect'] = $app->protect(function() use ($app) {
    $result = $app['mongo.pastes']->remove(
        array('createdAt' => array(
            '$lt' => new MongoDate($app['app.gc_interval']))
        ),
        array('safe' => true)
    );

    return $result['n'];
});

$app['mongo.pastes'] = function($app) {
    return $app['mongo.db']->paste;
};

$app['mongo.db'] = $app->share(function($app) {
    $mongo = new Mongo();
    return $mongo->trashbin;
});
