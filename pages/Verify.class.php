<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backbone/backbone/Control.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/backbone/backbone/Page.class.php");

class Verify extends Page {

    public function Verify() {
        $this->name = "index";
        $this->control = Control::getControl();

    }

    public function getRoute() {
        return "verify";
    }

    public function generateHTML() {
        return "";
    }

    public function isCacheable() {
        return FALSE;
    }

    public function doPost() {}

    public function doGet() {
        if($this->control->authenticationManager->verify()) {
            echo "verified";
        }
    }

    public $control;

}