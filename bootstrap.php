<?php
/*
 * This file is part of trashbin.
 *
 * (c) 2010 Igor Wiedler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// doctrine
require_once 'vendor/doctrine/lib/Doctrine/Core.php';
spl_autoload_register(array('Doctrine_Core', 'autoload'));
spl_autoload_register(array('Doctrine_Core', 'modelsAutoload'));

$manager = Doctrine_Manager::getInstance();

// autoloading
$manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_CONSERVATIVE);
Doctrine_Core::loadModels('models');

$conn = require 'config.php';

// twig
require_once 'vendor/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();
