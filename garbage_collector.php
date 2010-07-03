<?php
/*
 * This file is part of trashbin.
 *
 * (c) 2010 Igor Wiedler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require dirname(__FILE__) . '/bootstrap.php';

$q = Doctrine_Query::create()
	->delete('Paste p')
	->where('p.created_at < ?', date('Y-m-d H:i:s', strtotime('24 hours ago')));
$count = $q->execute();
echo "$count records deleted\n";
