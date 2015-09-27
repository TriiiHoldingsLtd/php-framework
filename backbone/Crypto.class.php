<?php
require_once("Control.class.php");

class Crypto {

    public function Crypto() {
        $this->control = Control::getControl();
        $this->salt[0] = "Yamv1GGzdBspCiwd";
        $this->salt[1] = "6Aorv3ZNN4BgxcTd";
        $this->salt[2] = "AxK45sxAJLycVihr";

        $this->AESKey[0] = "Gvg24rc5W8";
    }

    /**
     * Hash Functions
     */

    public function hashString($string, $index=0) {
        switch($index) {
            case 0:
                return $this->control->utility->sha256($string . $this->salt[$index]);
            case 1:
                return $this->control->utility->snefru($string . $this->salt[$index]);
            case 2:
                return $this->control->utility->whirlpool($string . $this->salt[$index]);
        }
    }

    public function randomHash($string) {
        $random = $this->randomHashIndex();
        return array($this->hashString($string, $random), $random);
    }

    public function randomHashIndex() {
        return rand(0, sizeof($this->salt)-1);
    }

    /**
     * AES 256
     */

    public function randomAESIndex() {
        return rand(0, sizeof($this->AESKey)-1);
    }

    public function AES256Encrypt($value, $key, $index=0) {
        switch($index) {
            case 0:
                return $this->AES256($value, $this->AESKey[$key]);
        }
    }

    public function AES256Decrypt($value, $key, $index=0) {
        switch($index) {
            case 0:
                return $this->RMAES256($value, $this->AESKey[$key]);
        }
    }

    public function AES256($sValue, $sSecretKey) {
        return rtrim(
            base64_encode(
                mcrypt_encrypt(
                    MCRYPT_RIJNDAEL_256,
                    $sSecretKey, $sValue,
                    MCRYPT_MODE_ECB,
                    mcrypt_create_iv(
                        mcrypt_get_iv_size(
                            MCRYPT_RIJNDAEL_256,
                            MCRYPT_MODE_ECB
                        ),
                        MCRYPT_RAND)
                )
            ), "\0"
        );
    }

    public function RMAES256($sValue, $sSecretKey) {
        return rtrim(
            mcrypt_decrypt(
                MCRYPT_RIJNDAEL_256,
                $sSecretKey,
                base64_decode($sValue),
                MCRYPT_MODE_ECB,
                mcrypt_create_iv(
                    mcrypt_get_iv_size(
                        MCRYPT_RIJNDAEL_256,
                        MCRYPT_MODE_ECB
                    ),
                    MCRYPT_RAND
                )
            ), "\0"
        );
    }


    public $control;
    public $salt = array();
    public $AESKey = array();
}