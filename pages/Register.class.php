<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backbone/backbone/Control.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/backbone/backbone/Page.class.php");

class Register extends Page {

    public function Register() {
        $this->name = "register";
        $this->control = Control::getControl();

    }

    public function getRoute() {
        return "register";
    }

    public function generateHTML() {
        if(!$this->control->authenticationManager->isLoggedIn()) {
            return $this->control->authenticationManager->outputRegisterForm();
        } else {
            return "You're already logged in.";
        }
    }

    public function isCacheable() {
        return FALSE;
    }

    public function doPost() {
        echo AuthenticationManager::$registerResponseMessage[$this->control->authenticationManager->register()];
    }

    public function doGet() {}

    public $control;

}