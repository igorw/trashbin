<?php
/*
 * This file is part of trashbin.
 *
 * (c) 2010 Igor Wiedler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function redirect($url)
{
	header("Location: $url");
}

function file_not_found()
{
	header("HTTP/1.0 404 Not Found");
}

function get_languages()
{
	$languages = array();
	foreach (glob('vendor/shjs/lang/sh_*.min.js') as $file)
	{
		$languages[] = str_replace(array('vendor/shjs/lang/sh_', '.min.js'), '', $file);
	}
	return $languages;
}

function language_exists($language)
{
	$language = basename($language);
	return file_exists("vendor/shjs/lang/sh_$language.min.js");
}
