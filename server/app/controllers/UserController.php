<?php

namespace App\controllers;

use App\core\Response;
use App\include\ApiFunctions;
use App\models\User;

class UserController 
{
    // GET -> read user data
    public function getUserData($params)
    {
        
    }

    // POST -> Insert user data
    public function insertUser()
    {
        Response::json(['message'=>'Ciao'], 200, '');
    }


}
