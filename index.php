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
		'index_url'	=> $index_url,
		'message'	=> 'file not found',
	));
	
	return;
}

$index_url = '.';
$create_url = '?q=create';
$view_url = '?q=view&id=%s';

$languages = array();
foreach (glob('vendor/shjs/lang/sh_*.min.js') as $file)
{
	$languages[] = str_replace(array('vendor/shjs/lang/sh_', '.min.js'), '', $file);
}
 
switch ($action)
{
	case 'index':
		$template = $twig->loadTemplate('index.html');

		$template->display(array(
			'create_url'	=> $create_url,
			'languages'	=> $languages,
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
				'languages'	=> $languages,
				'error_msg'	=> $error_msg,
				'paste'		=> $paste,
			));
			
			return;
		}
		
		$language = isset($_POST['language']) ? basename((string) $_POST['language']) : '';
		if (file_exists("vendor/shjs/lang/sh_$language.min.js"))
		{
			$paste->language = $language;
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
				'index_url'	=> $index_url,
				'message'	=> 'paste not found',
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
