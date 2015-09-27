<?php
class Utility {

    /**
     * Misc functions
     */
    public function elapsedTime($start, $end) {
        return ($end - $start);
    }

    public function getVar($name) {
        return isset($_GET[$name]) ? $_GET[$name] : "";
    }

    public function hasForwardedIp() {
        return $this->getForwardedIp() != NULL;
    }

    public function getForwardedIp() {
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if(isset($_SERVER['HTTP_X_REAL_IP'])) {
            return $_SERVER['HTTP_X_REAL_IP'];
        } else if (isset( $_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        return NULL;
    }

    public function correctRemoteAddr() {
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if(isset($_SERVER['HTTP_X_REAL_IP'])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_REAL_IP'];
        } else if (isset( $_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
    }

    function stringContains($needle, $haystack) {
        return strpos($haystack, $needle) !== false;
    }

    public function generateRandomCharSequence($length) {
        $str = "1234567890qwertyuiopasdfghjklzxcvbnm";
        $str = str_shuffle($str);
        $max = strlen($str);
        $output = "";
        for($i = 0; $i < $length; $i++) {
            $output .= $str[rand(0, $max-1)];
        }
        return $output;
    }

    /**
     * Censoring functions
     */

    public function censorIp($ip) {
        $split = explode(".", $ip);
        return $split[0] . "." . $split[1] . "." . "xxx.xx". substr($split[3], -1, 1);
    }

    public function censorMac($mac) {
        $split = explode("-", $mac);

        $output = "";
        for($i = 0; $i < (count($split) - 3); $i++) {
            $output .= $split[$i] . "-";
        }
        $output .= "xx-xx";

        $end =  "-" . $split[count($split)-1];

        return $output . $end;
    }

    /**
     * formatting & validation functions
     */

    public function toAlphaNumeric($string) {
        return preg_replace("/[^a-zA-Z0-9\s]/", "", $string);
    }

    public function toSecureEmailAddress($email) {
        $string = str_replace("'", "", $email);//Anti MySQL
        $string = str_replace('"', "", $string);//Anti XSS
        $string = str_replace('\"', "", $string);//Anti XSS
        $string = str_replace("", "", $string);//Anti XSS
        $string = str_replace("", "", $string);//Anti XSS
        $string = str_replace("--", "", $string);//Anti MySQLi
        $string = str_replace("", "", $string);//Anti XSS
        $string = str_replace(" ", "", $string);//NO SPACES!!@!@!@
        return $string;
    }

    public function toSecureURL($url) {
        $string = str_replace("'", "", $url);//Anti MySQL
        $string = str_replace('"', "", $string);//Anti XSS
        $string = str_replace('\"', "", $string);//Anti XSS
        $string = str_replace("", "", $string);//Anti XSS
        $string = str_replace("", "", $string);//Anti XSS
        $string = str_replace("--", "", $string);//Anti MySQLi
        $string = str_replace("", "", $string);//Anti XSS
        $string = str_replace(" ", "", $string);//NO SPACES!!@!@!@
        $pos = strpos($string, ' " ');
        if($pos === true) {
            return "http://www.google.com";
        }
        return $string;
    }


    public function isEmailValid($email) {
        // First, we check that there's one @ symbol, and that the lengths are right
        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }

        return true;
    }

    public function toSecureNumeric($number) {
        return preg_replace("/[^0-9]+/", "", $number);
    }

    /**
     * Hash functions
     */

    public function sha256($string) {
        return hash('sha256', $string);
    }
    public function sha384($string) {
        return hash('sha384', $string);
    }

    public function sha512($string) {
        return hash('sha512', $string);
    }

    public function snefru($string) {
        return hash('snefru', $string);
    }

    public function whirlpool($string) {
        return hash('whirlpool', $string);
    }


}