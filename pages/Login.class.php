<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backbone/backbone/Control.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/backbone/backbone/Page.class.php");

class Login extends Page {

    public function Login() {
        $this->name = "login";
        $this->control = Control::getControl();

    }

    public function getRoute() {
        return "login";
    }

    public function generateHTML() {
        if(!$this->control->authenticationManager->isLoggedIn()) {
            return $this->control->authenticationManager->outputLoginForm();
        }
    }

    public function isCacheable() {
        return FALSE;
    }

    public function doPost() {
        $msg = AuthenticationManager::$loginResponseMessage[$this->control->authenticationManager->login()];
        echo str_replace("{username}", $_SESSION["username"], $msg);
    }

    public function doGet() {}

    public $control;

}