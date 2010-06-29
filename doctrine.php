<?php
/*
 * This file is part of trashbin.
 *
 * (c) 2010 Igor Wiedler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'bootstrap.php';

// Configure Doctrine Cli
// Normally these are arguments to the cli tasks but if they are set here the arguments will be auto-filled
$config = array(
    'models_path'         =>  'models',
    'migrations_path'     =>  'migrations',
    'yaml_schema_path'    =>  'schema',
    'generate_models_options' => array(
        'pearStyle' => true,
        'generateTableClasses' => true,
        'baseClassPrefix' => 'Base',
        'baseClassesDirectory' => null,
    ),
);

$cli = new Doctrine_Cli($config);
$cli->run($_SERVER['argv']);
