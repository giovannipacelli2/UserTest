<?php

namespace App\models;

use App\core\App;

class User
{
    private static $table = 'user';

    // CHECK METHODS

    public static function describe()
    {
        return App::get('database')->describe(static::$table);
    }

    public static function checkEmail($email)
    {
        return App::get('database')->checkField(static::$table, 'email', $email);
    }

    // GET METHODS

    public static function getUser($id)
    {
        return App::get('database')->selectAllByField(static::$table, 'id', $id);
    }

    public static function getAllUsers($limit)
    {
        return App::get('database')->selectAll(static::$table, $limit);
    }

    // POST METHODS

    public static function createUser($data)
    {
        return App::get('database')->insert(static::$table, $data);
    }

    // PUT METHODS

    public static function updateUser($toUpdate, $where)
    {
        return App::get('database')->update(static::$table, $toUpdate, $where);

    }

    // DELETE METHODS

    public static function deleteUser($id)
    {
        return App::get('database')->deleteField(static::$table, 'id', $id);
    }
}
