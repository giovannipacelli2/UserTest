<?php

namespace App\include;

use App\core\Response;

class ApiFunctions
{
    private function __construct()
    {
    }

    /*------------------------------------------CONNECTION------------------------------------------*/

    // Check connection methods

    public static function checkMethod(string $method)
    {

        if ($_SERVER['REQUEST_METHOD'] !== $method) {

            Response::json([], 405, 'Method Not Allowed');

            exit();
        }
    }

    /*-----------------------------------------RECEIVED-DATA----------------------------------------*/

    public static function paramsUri(array $params, bool $empty = false)
    {

        if (!$_REQUEST) {
            Response::json([], 400, 'Bad request');
            exit();
        }

        $validation = self::existsAllParams($_REQUEST, $params, $empty);

        if (!$validation) {
            Response::json([], 400, 'Incorrect data in request');
            exit();
        }

        return $_REQUEST;

    }

    public static function getInput()
    {
        $data = file_get_contents('php://input')
                    ? file_get_contents('php://input')
                    : $_POST;

        if (!$data) {

            Response::json([], 400, 'No data');
            exit();
        }

        $result = json_decode($data);

        if (!$result ) {
            Response::json([], 400, 'Incorrect data');
        }

        try {

            $check = get_object_vars($result);

            if (empty($check)) {
                Response::json([], 400, 'Incorrect data');
            }
        } catch (\Error $e) {

            if (is_array($result)) {
                return $result;
            }
            Response::json([], 400, 'Error in data');
        }

        return $result;
    }

    // $limit unit is MB
    public static function getImgInput($limit = 5)
    {
        if (!$_FILES) {
            Response::json([], 400, 'No file');
            exit();
        }

        $file = array_key_first($_FILES);
        $file = $_FILES[$file];

        if ($file['error'] == UPLOAD_ERR_OK) {

            // Check file type
            $fileType = mime_content_type($file['tmp_name']);
            if ($fileType != 'image/jpeg' && $fileType != 'image/png') {

                Response::json([], 400, 'It possible to update only JPEG or PNG');
            } else {
                // Check file weight (example: 5MB)
                if ($file['size'] > 5 * 1024 * 1024) {

                    Response::json([], 400, 'File is too big. The maximum size allowed is 5MB');
                } else {

                    return $file;
                }
            }
        } else {
            Response::json([], 400, 'An error occurred while executing image upload. Try later.');
        }

    }

    /*----------------------------------CHECKING-THE-DATA-RECEIVED----------------------------------*/

    /*----------------CHECK-DUPLICATE-FIELD-------------------*/

    public static function checkDuplicate($arr, $arr_description = 'data')
    {

        $unique = array_unique($arr);

        if (count($arr) != count($unique)) {

            Response::json([], 400, "You cant't duplicate " . $arr_description);
            exit();
        } else {
            return true;
        }
    }

    /*---------------------CHECK-INPUT------------------------*/

    public static function inputChecker($data, $data_fields, $isDescribe = true)
    {

        if (!$data_fields) {
            exit();
        }

        if ($isDescribe) {

            $describe = $data_fields->fetchAll(\PDO::FETCH_ASSOC);
            $data_fields = self::getDataFromTable($describe);
        }

        $validation = self::existsAllParams($data, $data_fields);

        if (!$validation) {

            Response::json([], 400, 'Bad request');
            exit();
        }

        return true;

    }

    // Wants a DESCRIBE result statemento from DATABASE
    public static function getDataFromTable($describe)
    {

        // array containing the list of the NOT NULL fields
        $data_fields = [];

        // Push in $data_checker all NOT NULL fields
        foreach ($describe as $row) {

            $extra = isset($row['Extra']) ? $row['Extra'] : '';

            if ($row['Null'] == 'NO' && !preg_match('/auto_increment/', $extra)) {
                array_push($data_fields, $row['Field']);
            }

        }

        return $data_fields;
    }

    // check NOT NULL fields

    // Wants data as key=>value and fields as array of string
    public static function existsAllParams($data, $data_fields, $empty = false)
    {
        //cast sended data in associative array;
        $data = (array) $data;

        $check = true;

        // check input data integrity

        foreach ($data_fields as $param) {

            // $param = a NOT NULL field from existing table
            // $data = array to check
            $exists = array_key_exists($param, $data);

            // if param NOT EXISTS or an param has empty string

            if (!$exists || (!$empty && $data[$param] == '')) {
                $check = false;
            }

        }

        return $check;
    }

    /*-------CHECK-IF-THERE-ARE-ANY-INCORRECT-FIELDS----------*/

    // Wants data as associative array and fields as array of string
    public static function validateParams($data, $data_checker)
    {

        //cast sended data in associative array;
        $data = (array) $data;

        $check = true;

        // check input data integrity

        foreach ($data as $field => $value) {

            // $param = key of sended data
            // $data_checker = array with necessary field

            $exists = in_array($field, $data_checker);

            // if param NOT EXISTS

            if (!$exists || $value == '') {
                $check = false;
            }

        }

        return $check;
    }

    /*--------------------CHECK-UPDATE------------------------*/

    // Return empty array if there are all fields
    // Return "data_fields" if there are some of necessary fields

    public static function updateChecker($data, $data_fields, $isDescribe = true)
    {

        if (!$data_fields) {
            exit();
        }

        if ($isDescribe) {

            $describe = $data_fields->fetchAll(\PDO::FETCH_ASSOC);
            $data_fields = self::getDataFromTable($describe);
        }

        $validation = self::existsAllParams($data, $data_fields);

        if (!$validation) {

            $validation = self::validateParams($data, $data_fields);

            if (!$validation) {
                Response::json([], 400, 'Bad request');
                exit();
            }

            return $data_fields;
        }

        return [];

    }

    /*----------------------CHECK-DATE------------------------*/

    public static function validateDate($date, $format = 'Y-m-d H:i:s')
    {

        $d = \DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) == $date;
    }

    // check data format:
    // It can be 'Y-m-d H:i:s' or 'Y-m-d'

    public static function checkDate($date)
    {

        $validation = self::validateDate($date);

        if (!$validation) {

            if (!self::validateDate($date, 'Y-m-d')) {

                Response::json([], 400, 'Not valid format of date');
                exit();
            }

        }
    }

    public static function checkCorrectDates(array $date_arr): mixed
    {
        $now = new \DateTime('now');

        $date = [
            'start' => '',
            'end' => '',
        ];

        $count = 0;

        // Creates datatime objects from query data
        try {
            foreach ($date_arr as $key => $value) {

                if ($value != '') {
                    $date[$key] = new \DateTime($value);
                    $count++;
                }
            }
        } catch (\Exception $e) {
            Response::json([], 400, 'Error in date format!');

            return false;
        }

        // if the data is not there
        if ($count === 0) {

            Response::json([], 400, "The 'START' and 'END' values ​​cannot be both empty!");

            return false;

        }

        // If there is only one of the two data, it assigns today's date to the missing one

        elseif ($count < count($date)) {

            if ($date['start'] != '' && $date['start'] >= $now) {

                Response::json([], 400, "the 'START' date cannot be greater than today's date");

                return false;

            } elseif ($date['end'] != '' && $date['end'] <= $now) {

                Response::json([], 400, "the 'END' date cannot be less than today's date");

                return false;

            }

            if ($date['start'] == '') {
                $date['start'] = $now;
            }
            if ($date['end'] == '') {
                $date['end'] = $now;
            }

        } elseif ($count == count($date)) {

            if ($date['start'] > $date['end']) {

                Response::json([], 400, "the 'END' date cannot be less than 'START' date");

                return false;
            } elseif ($date['start'] == $date['end']) {

                Response::json([], 400, "The 'START' date can't be the same as the 'END' date");

                return false;
            }

        }

        return $date;

    }

    /*----------------------------------CREATE-NEW-ARRAY-FROM-DATA----------------------------------*/

    // Merge data by sales code

    public static function combineBySalesCode(\PDOStatement $stmt): array
    {

        $tmp_arr = [];

        // EXAMPLE OF DATA IN $tmp_arr

        /*---------------------------------------------------------------
        |    ..... ,                                                    |
        |    '0010' => [                                                |
        |        'plan_code' => string '0010'                           |
        |        'name' => string 'Spuntino on'                         |
        |        'creation_time' => string '2024-02-06 18:29:51'        |
        |        'last_update' => string '2024-02-06 18:31:53'          |
        |        'meal_code' => string 'AA11'                           |
        |        'meals' =>  [                                          |
        |           [                                                   |
        |               'id' => int 485                                 |
        |               'quantity' => float 80,                         |
        |           ],                                                  |
        |           [                                                   |
        |               'id' => int 579                                 |
        |               'quantity' => float 100,                        |
        |           ],                                                  |
        |         ],                                                    |
        |    ],                                                         |
        |    .....                                                      |
        ---------------------------------------------------------------*/

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

            //var_dump($row);
            $cur_code = $row['plan_code'];

            foreach ($row as $key => $value) {

                if (($key != 'user_id') &&
                     ($key != 'meal_code') &&
                     ($key != 'meal_name') &&
                     ($key != 'food_json') &&
                     ($key != 'meal_order') &&
                     ($key != 'plan_id')
                ) {
                    $tmp_arr[$cur_code][$key] = $value;
                } elseif ($key == 'food_json' && !$value) {
                    $tmp_arr[$cur_code]['meals'] = [];
                }
            }

            // MANAGE JSON FOOD IN MEALS
            $tmp['meal_code'] = $row['meal_code'];
            $tmp['meal_name'] = $row['meal_name'];
            $tmp['meal_order'] = $row['meal_order'];
            
            if ($row['food_json']) {
                $tmp['foods'] = (array) json_decode($row['food_json']);
            } else {
                $tmp['foods'] = [];
            }

            if (isset($tmp_arr[$cur_code]['meals'])) {

                if ($row['food_json']) {

                    array_push($tmp_arr[$cur_code]['meals'], $tmp);
                }

            } else {
                $tmp_arr[$cur_code]['meals'] = [$tmp];
            }

        }

        //echo json_encode($tmp_arr);
        //exit();

        return $tmp_arr;
    }

    /*-------------------------------------GENERATE-RANDOM-CODE-------------------------------------*/

    public static function rdmCode($bytes) : string
    {
        return bin2hex(random_bytes($bytes));
    }

    /*----------------------------------------TRUNCATE-FLOAT----------------------------------------*/

    public static function truncate($val, $f = '0')
    {
        if (($p = strpos($val, '.')) !== false) {
            $val = floatval(substr($val, 0, $p + 1 + $f));
        }

        return $val;
    }
}
