<?php
require_once("Database.class.php");
require_once("Utility.class.php");
require_once("Dispatcher.class.php");
require_once("AuthenticationManager.class.php");
require_once("Mailer.class.php");
require_once("Cache.class.php");

class Control {

    public function Control() {}

    public function initialize() {
        $this->database = new Database();

        $this->utility = new Utility();

        $this->dispatcher = new Dispatcher();

        $this->authenticationManager = new AuthenticationManager();

        $this->mailer = new Mailer();

        $this->cache = new Cache();

        if($this->utility->hasForwardedIp()) {
            $this->utility->correctRemoteAddr();
        }

        AuthenticationManager::startSession();
        AuthenticationManager::verifySession();
    }

    public $database;
    public $utility;
    public $dispatcher;
    public $authenticationManager;
    public $mailer;
    public $cache;

    public static function getControl() {
        if(!isset(Control::$control)) {
            Control::$control = new Control();
            Control::$control->initialize();
        }
        return Control::$control;
    }

    public static $control; //Control static variable, other classes can reach control by this variable.

}