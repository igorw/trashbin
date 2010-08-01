<?php
/*
 * This file is part of trashbin.
 *
 * (c) 2010 Igor Wiedler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$base_url = 'http://trashb.info/%s';

$info = fstat(STDIN);
$size = $info['size'];

if (!$size)
{
	echo "Pipe some content into here.\n";
	exit;
}

$content = '';
do
{
	$content .= fread(STDIN, 1024);
}
while (!feof(STDIN));

$context = stream_context_create(array(
	'http' => array(
		'method'  => 'POST',
		'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		'content' => http_build_query(array('submit' => 1, 'content' => $content)),
		'timeout' => 20,
	),
));
$result = file_get_contents(sprintf($base_url, 'create'), false, $context);

if ($result !== false)
{
	foreach ($http_response_header as $header)
	{
		if (preg_match('#^Location: (\w+)#', $header, $matches))
		{
			$paste_id = $matches[1];
			echo sprintf($base_url, $paste_id) . "\n";
		}
	}
}
