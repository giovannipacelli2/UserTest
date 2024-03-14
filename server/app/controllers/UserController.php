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
        $id = $params['id'];

        $userData = User::getUser($id);

        if ($userData->rowCount() === 0) {
            Response::json([], 404, 'User not found');
        }

        $userData = $userData->fetch(\PDO::FETCH_ASSOC);

        Response::json($userData, 200, '');
    }

    // GET -> read user data
    public function getAllUsers()
    {
        // check limit in uri params
        $params = ApiFunctions::paramsUri(['limit']);

        $limit = $params ? (int) $params['limit'] : 10;

        if ($limit === 0) {
            Response::json([], 400, 'Error in limit');
        }

        $usersData = User::getAllUsers($limit);

        if ($usersData->rowCount() === 0) {
            Response::json([], 404, 'Users not found');
        }

        $usersData = $usersData->fetchAll(\PDO::FETCH_ASSOC);

        Response::json(['users'=>$usersData], 200, '');
    }

    // POST -> Insert user data
    public function insertUser()
    {
        // get input data
        $data = (array) ApiFunctions::getInput();

        // Checks whether the inserted fields match
        // the fields in the database table

        $dataFields = User::describe();
        ApiFunctions::inputChecker($data, $dataFields);

        // check correctness of date
        ApiFunctions::checkDate($data['birthDate'], '');

        // validate Email
        $this->validateEmail($data['email']);

        $checkEmail = User::checkEmail($data['email']);

        if ($checkEmail) {
            Response::json([], 400, 'Email already exists');
        }

        User::createUser($data);

        //var_dump($data); exit;
        Response::json(['message'=>'User creation success'], 201, '');
    }

    private function validateEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::json([], 200, 'Email not valid');
        }
    }
}
