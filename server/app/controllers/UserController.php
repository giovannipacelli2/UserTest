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

        $this->checkUserData($data);

        $checkEmail = User::checkEmail($data['email']);

        if ($checkEmail) {
            Response::json([], 400, 'Email already exists');
        }

        User::createUser($data);

        Response::json(['message'=>'User creation success'], 201, '');
    }

    // PUT -> Update user data
    public function updateUser($params)
    {
        $id = $params['id'];

        $checkUser = User::getUser($id);

        if ($checkUser->rowCount() === 0) {
            Response::json([], 404, 'User not found');
        }

        // get input data
        $data = (array) ApiFunctions::getInput();

        // Checks whether the inserted fields match
        // SOME fields in the database table

        $dataFields = User::describe();
        ApiFunctions::updateChecker($data, $dataFields);

        $this->checkUserData($data);

        $update = User::updateUser($data, ['id'=>$id]);

        if ($update->rowCount() === 0) {

            Response::json([], 200, 'Update unsuccess');
        }

        Response::json(['message'=>'Update Success'], 200, '');

    }

    // DELETE -> delete user
    public function deleteUser($params)
    {
        $id = $params['id'];

        $user = User::getUser($id);

        if ($user->rowCount() === 0) {
            Response::json([], 404, 'User not found');
        }

        $delete = User::deleteUser($id);

        if (!$delete) {

            Response::json([], 400, 'Delete Unsuccess');
        }

        Response::json(['message'=>'Delete success'], 200, '');
    }

    /*-----------------------------------------------PRIVATE-FUNCTION*/

    private function validateEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::json([], 200, 'Email not valid');
        }
    }

    private function checkUserData($data)
    {

        // check correctness of date
        if (isset($data['birthDate'])) {
            ApiFunctions::checkDate($data['birthDate'], '');
        }

        // validate Email
        if (isset($data['email'])) {
            $this->validateEmail($data['email']);
        }

        return true;
    }
}
