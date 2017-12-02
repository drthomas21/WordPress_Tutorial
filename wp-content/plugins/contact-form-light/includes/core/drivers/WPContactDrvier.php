<?php
namespace Contact_Form_Light\Core\Drivers;

class WPContactDriver {
    protected static $Instance = null;

    public static function getInstance(): self {
        if(self::$Instance == null) {
            self::$Instance = new static();
        }

        return self::$Instance;
    }

    protected function __construct() {

    }

    protected function enqueueAssets() {

    }

    protected function registerShortCode() {

    }

    protected function ajaxHandler() {
        
    }
}
