<?php
namespace Youtube_Vids\Controllers;
class AdminController {
    const PAGE_TITLE = "YouTube Profile Vids";
    const MENU_TITLE = "YouTube Vids";
    const MENU_SLUG = "youtube-vids";

    const ACTION_AUTH = "authorize";

    private static $Instance = null;

    public static function getInstance(): self {
        if(self::$Instance == null){
            self::$Instance = new self();
        }

        return self::$Instance;
    }

    protected function __construct() {
        add_action( 'admin_menu', function() {
            $this->adminMenu();
        });
    }

    protected function getRedirectURL(): string {
        return admin_url("options-general.php?page=".self::MENU_SLUG."&action=".self::ACTION_AUTH);
    }

    protected function adminMenu() {
        add_options_page(self::PAGE_TITLE,self::MENU_TITLE,"manage_options",self::MENU_SLUG,function() {
            $this->display();
        });
    }

    protected function display() {
        $Driver = new \Youtube_Vids\Drivers\GoogleApiDriver();
        $Driver->setRedirectUri($this->getRedirectURL());
        $Driver->prepareScopes();

        if($_GET['action'] == self::ACTION_AUTH) {
            $Driver->authenticate($_GET['code']);
        }

        include YOUTUBE_VIDS_DIR.'/templates/index.php';
    }
}
