<?php
require_once("Control.class.php");
require_once("phpfastcache/3.0.0/phpfastcache.php");
class Cache {

    public function Cache() {
        $this->control = Control::getControl();
        $this->phpFastCache = phpFastCache();
    }

    public function prepareObject($object, $insertion=TRUE) {
        if($insertion) {
            return serialize($object);
        } else {
            return unserialize($object);
        }
    }

    public $phpFastCache;
    public $control;

}