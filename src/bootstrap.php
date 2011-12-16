<?php
/*
 * This file is part of trashbin.
 *
 * (c) 2010 Igor Wiedler
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Predis\Silex\PredisServiceProvider;

use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;

use Symfony\Component\Finder\Finder;

/* prevent direct access */
if (!$app) {
    exit;
}

$app->register(new TwigServiceProvider(), array(
    'twig.path'         => __DIR__.'/../views',
    'twig.options'      => array('cache' => __DIR__.'/../cache/twig', 'debug' => true),
));

$app->register(new UrlGeneratorServiceProvider());

$app->register(new PredisServiceProvider());

$app['app.languages'] = function () {
    $languages = array();
    $finder = new Finder();
    foreach ($finder->name('*.min.js')->in(__DIR__.'/../vendor/shjs/lang') as $file) {
        if (preg_match('#sh_(.+).min.js#', basename($file), $matches)) {
            $languages[] = $matches[1];
        }
    }
    return $languages;
};

$app['footer'] = 'Hosted by <a href="https://affiliates.nexcess.net/idevaffiliate.php?id=1184">Nexcess.net</a>';
