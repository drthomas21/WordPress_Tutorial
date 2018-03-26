<?php
namespace Themes\Superwordpressguide_V3\Util\Ajax;

class NgTemplateService {
    const AJAX_ACTION = "ngTemplate";
    private static $Instance = null;

    public static function getInstance(): self {
        if(self::$Instance == null) {
            self::$Instance = new self();
        }

        return self::$Instance;
    }

    protected function __construct() {
        \add_action("wp_ajax_".self::AJAX_ACTION,function() {
            $this->loadTemplate($_GET['name']);
        });
        \add_action("wp_ajax_nopriv_".self::AJAX_ACTION,function() {
            $this->loadTemplate($_GET['name']);
        });
    }

    protected function loadTemplate(string $name) {
        $filepath = get_template_directory()."/templates/{$name}.php";
        if(file_exists($filepath)) {
            http_response_code (200);
            include $filepath;
        } else {
            http_response_code (404);
            include get_template_directory()."/templates/404.php";
        }
        exit();
    }
}
