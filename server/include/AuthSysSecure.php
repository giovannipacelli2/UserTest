<?php

namespace App\include;

class AuthSysSecure
{
    protected $cookie;

    public function __construct()
    {
        $this->cookie = [
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax',
        ];
        $this->initSession();
    }

    private function initSession()
    {
        ini_set('session.use_cookie', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_trans_id', '0');
        ini_set('session.use_strict_mode', '1');

        session_set_cookie_params($this->cookie);

    }

    protected function cleanInput(&$input, $type, $maxLenght = true)
    {
        if ($maxLenght === true) {
            $cleanInput = mb_substr($input, 0, 200);
        } else {
            $cleanInput = &$input;
        }

        switch ($type) {
            case 'str':
                $cleanInput = filter_var($cleanInput, FILTER_SANITIZE_SPECIAL_CHARS);
                break;

            case 'int':
                $cleanInput = filter_var($cleanInput, FILTER_SANITIZE_NUMBER_INT);
                break;

            case 'float':
                $cleanInput = filter_var($cleanInput, FILTER_SANITIZE_NUMBER_FLOAT);
                break;

            default:
                $cleanInput = filter_var($cleanInput, FILTER_SANITIZE_URL);
        }

        return $cleanInput;
    }

    public static function generatePassword() : string
    {
        return bin2hex(random_bytes(10));
    }

    public static function getTokenCSRF() : string
    {
        return bin2hex(random_bytes(64));
    }

    public function XSS($input)
    {
        return htmlspecialchars(strip_tags($input));
    }
}
