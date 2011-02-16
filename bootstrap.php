<?php
/*
 * This file is part of trashbin.
 *
 * (c) 2010 Igor Wiedler
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\ClassLoader\UniversalClassLoader;
use Symfony\Component\Config\FileLocator;

$loader = new UniversalClassLoader;
$loader->registerPrefixes(array(
	'Twig_'			=> __DIR__ . '/vendor/Twig/lib',
));
$loader->register();

// di container
$container = new ContainerBuilder();

$env = isset($_SERVER['APP_ENV']) ? $_SERVER['APP_ENV'] : 'dev';

$loader = new YamlFileLoader($container, new FileLocator(__DIR__));
$loader->load("$env.config.yml");
