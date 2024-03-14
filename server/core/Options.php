<?php
    use App\core\Response;

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == "OPTIONS") {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Request-Headers, Authorizzation");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        //header("HTTP/1.1 200 OK");
        Response::json(['message'=>'OK'], 200, '');
    }

?>