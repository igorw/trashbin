<?php
/*
 * This file is part of trashbin.
 *
 * (c) 2010 Igor Wiedler
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once __DIR__.'/silex.phar';
require __DIR__.'/bootstrap.php';

$pastes = $container->get('mongo.pastes');

$result = $pastes->remove(
    array('createdAt' => array(
        '$lt' => new MongoDate(strtotime($container->getParameter('app.gc_interval'))))
    ),
    array('safe' => true)
);

echo "{$result['n']} records deleted\n";
