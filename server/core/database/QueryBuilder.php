<?php

namespace App\core\database;

use App\core\Response;

class QueryBuilder
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /*----------------------------------------------------CHECK-METHODS----------------------------------------------------*/

    public function describe($table_name)
    {
        try {

            $q = 'DESCRIBE ' . $table_name . ';';
            $stmt = $this->pdo->prepare($q);

            $stmt->execute();

            return $stmt;

        } catch (\Exception $e) {

            self::manageError($e);

        }
    }

    public function checkField($table_name, $field, $values)
    {
        $values = self::queryValuesBuilder($values);

        try {

            $q = 'SELECT * FROM ' . $table_name .
            ' WHERE BINARY(' . $field . ') IN (' . implode(', ', array_column($values, 'placeholder')) . ');';

            $stmt = $this->pdo->prepare($q);

            foreach ($values as $param) {
                $stmt->bindParam($param['placeholder'], $param['value']);
            }

            $stmt->execute();

            if (!$stmt || $stmt->rowCount() == 0 || $stmt->rowCount() != count($values)) {
                return false;
            }
            if ($stmt->rowCount() == count($values)) {
                return true;
            }

        } catch (\Exception $e) {

            self::manageError($e);

        }
    }

    /*-----------------------------------------------------GET-METHODS-----------------------------------------------------*/

    public function selectAll($table_name)
    {
        try {

            $q = 'SELECT * FROM ' . $table_name . ';';
            $stmt = $this->pdo->prepare($q);

            $stmt->execute();

            return $stmt;

        } catch (\Exception $e) {

            self::manageError($e);

        }
    }

    public function selectAllByField($table_name, $field, $values, $order = '')
    {

        $values = self::queryValuesBuilder($values);

        $qOrder = '';

        if ($order !== '') {
            $qOrder = ' ORDER BY ' . $order . ' ASC';
        }

        try {

            $q = 'SELECT * FROM ' . $table_name .
            ' WHERE ' . $field . ' IN (' . implode(', ', array_column($values, 'placeholder')) . ')' .
             $qOrder . ';';

            $stmt = $this->pdo->prepare($q);

            foreach ($values as $param) {
                $stmt->bindParam($param['placeholder'], $param['value']);
            }

            $stmt->execute();

            return $stmt;

        } catch (\Exception $e) {

            self::manageError($e);

        }
    }

    public function selectAllByMoreFields($table_name, $fieldsArr, $order = '')
    {

        $fields = self::queryFieldsBuilder($fieldsArr);

        $qOrder = '';

        if ($order !== '') {
            $qOrder = ' ORDER BY ' . $order . ' ASC';
        }

        try {

            $q = 'SELECT * FROM ' . $table_name .
            ' WHERE ' . implode(' AND ', array_column($fields, 'set')) .
            $qOrder . ';';

            $stmt = $this->pdo->prepare($q);

            foreach ($fields as $param) {
                $stmt->bindParam($param['placeholder'], $param['value']);
            }

            $stmt->execute();

            return $stmt;

        } catch (\Exception $e) {
            echo $e->getMessage();
            self::manageError($e);

        }
    }

    /*-----------------------------------------------------POST-METHODS----------------------------------------------------*/

    public function insert($table_name, $data)
    {
        // wants $data like this:
        // (array) :
        //      'product_id' => '0010',
        //      'n_products' => '3'

        $params = self::queryFieldsBuilder($data);

        try {

            $q = 'INSERT INTO ' . $table_name . ' ( ' . implode(', ', array_column($params, 'field')) . ' ) '
                    . 'VALUES (' . implode(', ', array_column($params, 'placeholder')) . ');';

            $stmt = $this->pdo->prepare($q);

            foreach ($params as $param) {

                $type = null;

                if (is_int($param['value'])) {
                    $type = \PDO::PARAM_INT;
                } else {
                    $type = \PDO::PARAM_STR;
                }

                $stmt->bindParam($param['placeholder'], $param['value'], $type);
            }

            $stmt->execute();

            return $stmt;

        } catch (\Exception $e) {

            if ($e->getCode() != 23000) {
                self::manageError($e);
            }
            exceptionHandler($e);

            return false;

        }
    }

    /*------------------------------------------------------PUT-METHODS----------------------------------------------------*/

    public function update($table_name, $data, $fieldsArr)
    {
        // wants $data and $fieldsArr like this:
        // (array) :
        //      'product_id' => '0010',
        //      'n_products' => '3'

        $params = self::queryFieldsBuilder($data);
        $fields = self::queryFieldsBuilder($fieldsArr, 'p_');

        try {

            $q = 'UPDATE ' . $table_name . ' SET ' . implode(', ', array_column($params, 'set')) .
                    ' WHERE ' . implode(' AND ', array_column($fields, 'set')) . ';';

            $stmt = $this->pdo->prepare($q);

            foreach ($params as $param) {
                $stmt->bindParam($param['placeholder'], $param['value']);
            }
            foreach ($fields as $param) {
                $stmt->bindParam($param['placeholder'], $param['value']);
            }

            $stmt->execute();

            return $stmt;

        } catch (\Exception $e) {

            if ($e->getCode() != 23000) {
                self::manageError($e);
            }

            exceptionHandler($e);

            return false;

        }
    }

    /*---------------------------------------------------DELETE-METHODS----------------------------------------------------*/

    public function deleteField($table_name, $field, $values)
    {
        $values = self::queryValuesBuilder($values);

        try {

            $q = 'DELETE FROM ' . $table_name .
            ' WHERE ' . $field . ' IN (' . implode(', ', array_column($values, 'placeholder')) . ');';

            $stmt = $this->pdo->prepare($q);

            foreach ($values as $param) {
                $stmt->bindParam($param['placeholder'], $param['value']);
            }

            $stmt->execute();

            if (!$stmt || $stmt->rowCount() == 0 || $stmt->rowCount() != count($values)) {
                return false;
            }
            if ($stmt->rowCount() == count($values)) {
                return true;
            }

        } catch (\Exception $e) {

            self::manageError($e);

        }
    }

    // wants condition as [ 'field' => 'field_name', 'value' => value ]
    public function notInDelete($table_name, $field, $values, $condition)
    {

        $values = self::queryValuesBuilder($values);

        try {

            $q = 'DELETE FROM ' . $table_name .
            ' WHERE ' . $field . ' NOT IN (' . implode(', ', array_column($values, 'placeholder')) . ')'
            . ' AND ' . $condition['field'] . '=:code;';

            $stmt = $this->pdo->prepare($q);

            foreach ($values as $param) {
                $stmt->bindParam($param['placeholder'], $param['value']);
            }

            $stmt->bindParam(':code', $condition['value'], \PDO::PARAM_STR);

            $stmt->execute();

            return $stmt;

        } catch (\Exception $e) {

            self::manageError($e);

        }
    }

    /*---------------------------------------------------GET-INFO-FUNCTIONS------------------------------------------------*/

    public function getLastId()
    {
        return $this->pdo->lastInsertId();
    }

    public function countRows($table_name, $field, $values)
    {
        $stmt = self::selectAllByField($table_name, $field, $values);

        if (!$stmt || $stmt->rowCount() == 0) {
            return 0;
        }

        return $stmt->rowCount();

    }

    /*---------------------------------------------------PRIVATE-FUNCTIONS-------------------------------------------------*/

    // Wants $values as array:  [value1, value2]
    // or single value as string: 'value'

    protected function queryValuesBuilder($values)
    {
        if (is_array($values)) {
            $tmp = [];

            for ($i = 0; $i < count($values); $i++) {
                array_push($tmp, [
                    'placeholder'=> ':pl' . $i,
                    'value'=> $values[$i],
                ]);
            }

            $values = $tmp;
            $tmp = null;
        } else {
            $tmp = [
                'placeholder'=> ':pl',
                'value'=> $values,
            ];
            $values = [];
            array_push($values, $tmp);
        }

        return $values;
    }

    protected function queryFieldsBuilder($fields, $pre = '')
    {
        $tmp = [];

        $count = 0;

        foreach ($fields as $key=>$value) {

            $key = htmlspecialchars(strip_tags($key));

            if ( !is_int($value) ) {
                $value = htmlspecialchars(strip_tags($value));
            }

            $pl = 'pl' . $count;

            $row['field'] = $key;
            $row['placeholder'] = ':' . $pre . $pl;
            $row['value'] = $value;
            $row['set'] = $key . '=:' . $pre . $pl;

            array_push($tmp, $row);
            $count++;
        }

        return $tmp;
    }

    protected function manageError($e)
    {
        exceptionHandler($e);

        Response::json([], 500, 'An error occurred while executing the query. Try later.');
    }
}
