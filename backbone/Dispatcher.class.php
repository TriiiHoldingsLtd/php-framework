<?php
require_once("Control.class.php");

class Dispatcher {

    public function Dispatcher() {
        $this->loadPages($_SERVER['DOCUMENT_ROOT']."/backbone/pages/");
    }

    public function handleRoute($path) {
        $currentPage = NULL;
        foreach($this->pages as $page) {
            if ($page->getRoute() == $path) {
                $currentPage = $page;
            }
        }

        if ($currentPage != NULL) {
            if ($currentPage->hasPost()) {
                $currentPage->doPost();
            }

            if ($currentPage->hasGet()) {
                $currentPage->doGet();
            }

            if($currentPage->isCacheable()) {
                $cache = Control::getControl()->cache->phpFastCache;
                $cachedPage = $cache->get($currentPage->getName() . "_page");
                if($cachedPage == null) {
                    //TODO compensate for username replacement within the cache for login scripts
                    $html = $currentPage->getHTML();
                    $cache->set($currentPage->getName() . "_page" , $html, 600);
                    echo $html;
                } else {
                    //TODO compensate for username replacement within the cache for login scripts
                    echo $cachedPage;
                }
            } else {
                echo $currentPage->getHTML();
            }

            //echo "DEBUG: ELAPSED TIME: ". Control::getControl()->utility->elapsedTime($currentPage->startTime, $currentPage->endTime);
        } else {
            echo "Not found";
        }
    }

    public function getPage($title) {
        foreach ($this->pages as $page) {
            if($page->getName() == strtolower($title)) {
                return $page;
            }
        }
        return NULL;
    }

    public function loadPages($path) {
        $workDirectory = opendir($path);
        $i = 0;
        while(false !== ($entry = readdir($workDirectory))) {
            if($entry == "." || $entry == "..") continue;
            if(strpos($entry, ".class.php") !== false) {
                $name = str_replace(".class.php", "", $entry);

                if(is_readable($path . $entry)) {
                    require_once($path . $entry);
                }

                $object = new $name(strtolower($name));
                $this->pages[$i] = $object;
                $i++;
            }
        }
    }


    public $pages = array();
}
