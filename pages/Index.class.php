<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backbone/backbone/Control.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/backbone/backbone/Page.class.php");

class Index extends Page {

    public function Index() {
        $this->name = "index";
        $this->control = Control::getControl();

    }

    public function getRoute() {
        return "";
    }

    public function generateHTML() {
        return "html";
    }

    public function isCacheable() {
        return FALSE;
    }

    public function doPost() {}

    public function doGet() {}

    public $control;

}