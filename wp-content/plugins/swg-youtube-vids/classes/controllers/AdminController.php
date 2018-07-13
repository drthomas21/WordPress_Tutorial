<?php
namespace Youtube_Vids\Controllers;
class AdminController {
    const PAGE_TITLE = "YouTube Profile Vids";
    const MENU_TITLE = "YouTube Vids";
    const MENU_SLUG = "youtube-vids";

    const ACTION_AUTH = "authorize";

    const WP_AJAX = "youtube-vids";
    const WP_AJAX_FILTER = "youtube-vids-filter";

    private static $Instance = null;
    private $Driver = null;

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
                $this->Driver->authenticate($_GET['code']);
                wp_safe_redirect($this->getPageURL());
                exit();
            }
        });

        add_action("wp_ajax_".self::WP_AJAX,function() {
            $Controller = new PageController();
            $type = array_key_exists("type",$_GET) ? $_GET['type'] : '';
            $offset = array_key_exists("offset",$_GET) ? intval($_GET['offset']) : 0;
            $limit = array_key_exists("limit",$_GET) ? intval($_GET['limit']) : 10;

            $temp = [];

            //var_dump($offset,$limit); exit;
            switch($type) {
                case 'popular':
                    $temp = $Controller->getPopularVideos($offset,$limit);
                    break;
                case 'recent':
                    $temp = $Controller->getRecentVideos($offset,$limit);
                    break;
            }

            $videos = [];
            foreach($temp as $item) {
                $videos[] = [
                    "id" => $item->id,
                    "description" => $item->Snippet->description,
                    "published" => date("U",strtotime($item->Snippet->publishedAd)),
                    "title" => $item->Snippet->title,
                    "thumbnails" => $item->Snippet->thumbnails
                ];
            }

            wp_send_json(['videos' => $videos]);
        });

        add_action("wp_ajax_".self::WP_AJAX_FILTER,function() {
            $Controller = new PageController();
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                $fp = fopen("php://input","r");
                $raw = "";
                while($fp && !feof($fp)) $raw .= fgets($fp);
                fclose($fp);

                $Data = json_decode($raw);
                if($Data && $Data->id && $Data->type) {
                    switch($Data->type) {
                        case 'recent':
                            $Controller->toggledRecentVideo($Data->id);
                            break;
                        case 'popular':
                        $Controller->toggledPopularVideo($Data->id);
                            break;
                    }
                }
            }


            $filters = $Controller->getFlaggedIds();

            wp_send_json(['filters' => $filters]);
        });

        try {
            $this->Driver = new \Youtube_Vids\Drivers\GoogleApiDriver();
            $this->Driver->setRedirectUri($this->getRedirectURL());
            $this->Driver->prepareScopes();
        } catch (\Exception $e) {
            //Looks like we have some issue creating $this->Driver
            $this->Driver = null;
        }
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
        $token = [];

        if($this->Driver) {
            $this->Driver->refreshAccessToken();
            $token = $this->Driver->getAccessToken();
        }

        $isValid = $this->Driver && $this->Driver->checkAccessToken();
        $authUrl = $this->Driver ? $this->Driver->createAuthUrl() : "";

        include YOUTUBE_VIDS_DIR.'/templates/index.php';
    }
}
