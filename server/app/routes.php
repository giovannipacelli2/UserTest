<?php

/*---------------------------USER---------------------------*/

$router->get('user/{id}', 'UserController@getUserData');
$router->get('users', 'UserController@getAllUsers');

// ACTIONS

$router->post('user', 'UserController@insertUser');

$router->delete('user', 'UserController@deleteUser,id');
