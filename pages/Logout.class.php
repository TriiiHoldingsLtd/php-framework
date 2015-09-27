<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backbone/backbone/Control.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/backbone/backbone/Page.class.php");

class Logout extends Page {

    public function Logout() {
        $this->name = "logout";
        $this->control = Control::getControl();

    }

    public function getRoute() {
        return "logout";
    }

    public function generateHTML() {
        echo $this->control->authenticationManager->logout();
        if(!$this->control->authenticationManager->isLoggedIn()) {
            return "You are now logged out.";
        } else {
            return "You're already logged in.";
        }
    }

    public function isCacheable() {
        return FALSE;
    }

    public function doPost() {
        //echo $this->control->authenticationManager->logout();
    }

    public function doGet() {}

    public $control;

}