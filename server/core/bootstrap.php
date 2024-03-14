<?php

use App\core\App;
use App\core\database\Connection;
use App\core\database\FoodQueryBuilder;
use App\include\AuthSysToken;

App::bind('config', require './config/config.php');
App::bind('sec_conf', require './config/secure_config.php');
App::bind('email', require './config/email_config.php');

$pdo = Connection::make(App::get('config')['database']);
AuthSysToken::loadKey(App::get('sec_conf'));

App::bind('database', new FoodQueryBuilder($pdo));
