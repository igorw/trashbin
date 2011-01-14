<?php
/*
 * This file is part of trashbin.
 *
 * (c) 2010 Igor Wiedler
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Symfony\Component\HttpFoundation\UniversalClassLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

$loader = new UniversalClassLoader;
$loader->registerPrefixes(array(
	'Twig_'			=> __DIR__ . '/vendor/Twig/lib',
));
$loader->register();

// di container
$container = new ContainerBuilder();

$env = isset($_SERVER['APP_ENV']) ? $_SERVER['APP_ENV'] : 'dev';

$loader = new YamlFileLoader($container);
$loader->load(__DIR__."/$env.config.yml");
