<?php
require_once("backbone/Control.class.php");
Control::getControl()->dispatcher->handleRoute(Control::getControl()->utility->getVar("module"));

