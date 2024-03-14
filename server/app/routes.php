<?php

/*---------------------------USER---------------------------*/

$router->get('user/{id}', 'UserController@getUserData');
$router->get('users', 'UserController@getAllUsers');

$router->post('user', 'UserController@insertUser');

$router->put('user', 'UserController@updateUser,id');

$router->delete('user', 'UserController@deleteUser,id');
