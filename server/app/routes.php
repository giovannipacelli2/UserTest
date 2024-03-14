<?php

/*---------------------------USER---------------------------*/

$router->get('gplans/user/{id}', 'UserController@getUserData');

// ACTIONS

$router->post('gplans/user', 'UserController@signup');


$router->delete('gplans/user', 'UserController@deleteUser,id');