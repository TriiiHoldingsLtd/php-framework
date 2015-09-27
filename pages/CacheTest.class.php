<?php
require_once($_SERVER['DOCUMENT_ROOT']."/backbone/backbone/Control.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/backbone/backbone/Page.class.php");

class CacheTest extends Page {

    public function CacheTest() {
        $this->name = "cache";
        $this->control = Control::getControl();

    }

    public function getRoute() {
        return "cache";
    }

    public function generateHTML() {
        return "<br />html";
    }

    public function isCacheable() {
        return TRUE;
    }

    public function doPost() {}

    public function doGet() {}

    public $control;

}