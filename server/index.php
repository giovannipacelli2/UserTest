<?php

require './vendor/autoload.php';
require './core/error-handler.php';

use App\core\Request;
use App\core\Router;

require './core/Options.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorizzation, X-Requested-With, cors");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json");

$database = require './core/bootstrap.php';

$method = Request::method();
$uri = Request::uri();

$router = Router::load( './app/routes.php' );

$router->direct( $uri, $method );
