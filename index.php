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

$action = isset($_GET['q']) ? (string) $_GET['q'] : 'index';
if (!in_array($action, array('index', 'create', 'view')))
{
	header("HTTP/1.0 404 Not Found");
	
	$template = $twig->loadTemplate('error.html');

	$template->display(array(
		'message' => 'file not found',
	));
	
	return;
}

$index_url = '.';
$create_url = '?q=create';
$view_url = '?q=view&id=%s';
 
switch ($action)
{
	case 'index':
		$template = $twig->loadTemplate('index.html');

		$template->display(array(
			'create_url' => $create_url,
		));
	break;
	
	case 'create':
		if (!isset($_POST['submit']))
		{
			header("Location: $index_url");
			return;
		}
		
		$paste = new Paste();
		$paste->content = isset($_POST['content']) ? preg_replace('#\\r?\\n#', "\n", (string) $_POST['content']) : '';
		
		if (trim($paste->content) === '')
		{
			$error_msg = 'you must enter some content';
			
			$template = $twig->loadTemplate('index.html');

			$template->display(array(
				'create_url'	=> $create_url,
				'error_msg'	=> $error_msg,
				'paste'		=> $paste,
			));
			
			return;
		}
		
		$paste->save();
		
		header('Location: ' . sprintf($view_url, (int) $paste->id));
		return;
	break;
	
	case 'view':
		$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
		$paste = Doctrine_Core::getTable('Paste')->find($id);
		
		if (!$paste)
		{
			header("HTTP/1.0 404 Not Found");

			$template = $twig->loadTemplate('error.html');

			$template->display(array(
				'message' => 'paste not found',
			));

			return;
		}
		
		$template = $twig->loadTemplate('view.html');

		$template->display(array(
			'paste'		=> $paste,
			'index_url'	=> $index_url,
		));
	break;
}
