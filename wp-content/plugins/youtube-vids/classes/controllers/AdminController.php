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

        add_action("admin_init",function() {
            if($_GET['action'] == self::ACTION_AUTH && isset($_GET['code'])) {
                $Driver = $this->getDriver();
                $Driver->authenticate($_GET['code']);
                wp_safe_redirect($this->getPageURL());
                exit();
            }
        });
    }

    protected function getDriver(): \Youtube_Vids\Drivers\GoogleApiDriver {
        $Driver = new \Youtube_Vids\Drivers\GoogleApiDriver();
        $Driver->setRedirectUri($this->getRedirectURL());
        $Driver->prepareScopes();
        return $Driver;
    }

    protected function getRedirectURL(): string {
        return admin_url("options-general.php?page=".self::MENU_SLUG."&action=".self::ACTION_AUTH);
    }

    protected function getPageURL(): string {
        return admin_url("options-general.php?page=".self::MENU_SLUG);
    }

    protected function adminMenu() {
        add_options_page(self::PAGE_TITLE,self::MENU_TITLE,"manage_options",self::MENU_SLUG,function() {
            $this->display();
        });
    }

    protected function display() {
        $Driver = $this->getDriver();
        $Driver->setAccessToken();
        $token = $Driver->getAccessToken();
        
        $accessToken = isset($token['access_token']) ? $token['access_token'] : "";
        $createDate = isset($token['created']) ? date("Y-m-d H:i:s",$token['created'] - (8 * 3600)) : "";
        $expireDate = isset($token['created']) && $token['expires_in'] ? date("Y-m-d H:i:s",$token['created'] + $token['expires_in'] - (8 * 3600)) : "";

        include YOUTUBE_VIDS_DIR.'/templates/index.php';
    }
}
