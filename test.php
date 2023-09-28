<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/config.php';

use Model\Services\DataAccessMDB;
use MongoDB\Driver\Manager;

//DataAccessMDB::InsertDocument('world', 'items', ['id', 'type', 'components', 'attributes', 'locationType', 'state', 'inventory', 'description'], ['1', 'knife', '', '', '1', '0', '1', 'A knife']);
//DataAccessMDB::InsertDocument('world', 'items', ['id', 'type', 'components', 'attributes', 'locationType', 'state', 'inventory', 'description'], ['1', 'spoon', '', '', '1', '0', '1', 'A spoon']);
//echo var_dump(json_encode(DataAccessMDB::FindDocument('world', 'items', ['locationType', 'inventory'], ['1', '1'])));
$content  = json_encode(DataAccessMDB::FindDocument('world', 'items', ['name'], ['pepe']));
$file = fopen('test.txt', 'a+');
fwrite($file, $content . "\n");
fclose($file);