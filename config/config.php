<?php
use Model\Services\DataAccessMDB;
define('SITE_NAME', 'Test');
define('APP_ROOT', dirname(dirname(__FILE__)));

define('DB', APP_ROOT . '/app/database/database.db');
//define('MONGO_URL', "mongodb://localhost:27017");
DataAccessMDB::url("mongodb://localhost:27017");