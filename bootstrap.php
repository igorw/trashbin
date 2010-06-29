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
$manager = Doctrine_Manager::getInstance();

$conn = require 'config.php';

// twig
require_once 'vendor/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();
