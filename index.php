<?php
/*
 * This file is part of trashbin.
 *
 * (c) 2010 Igor Wiedler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require 'bootstrap.php';

// set up twig
$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader, array(
	'cache' => 'cache/twig',
	'debug' => true,
));
 
$template = $twig->loadTemplate('index.html');

$template->display(array(
	'create_url' => '/create',
));
