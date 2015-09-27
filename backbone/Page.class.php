<?php
abstract class Page {

    public function Page($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public abstract function getRoute();
    public abstract function generateHTML();
    public abstract function doPost();
    public abstract function doGet();

    //page can be globally cached (not many user specific details)
    public abstract function isCacheable();

    public function getHTML() {
        $this->startTime = microtime(true);
        $generation = $this->generateHTML();
        $this->endTime = microtime(true);
        return $generation;
    }

    public function hasPost() {
        if (!empty($_POST)) {
            return TRUE;
        }
        return FALSE;
    }

    public function hasGet() {
        if (!empty($_GET)) {
            if (sizeof($_GET) > 1) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public $assets = array();
    public $name;
    public $startTime;
    public $endTime;
    
    public function setAsset($key, $asset) {
        $this->assets[$key] = $asset;
    }

    public function getAsset($key) {
        if(isset($this->asset[$key])) {
            return $this->asset[$key];
        }
        return NULL;
    }
}