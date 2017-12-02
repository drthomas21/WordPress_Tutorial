<?php
namespace Contact_Form_Light\Core\Drivers;

class WPContactDriver {
    const ACTION_SEND_MAIL = "contact_send_mail";
    const SHORTCODE = "contat_form_light";
    protected static $Instance = null;
    protected static $assetsDefault = [
        "src" => "",
        "ver" => "",
        "deps" => [],
        "footer" => true
    ];
    protected $assets = [
        "jquery" => [
            "src" => "https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js",
            "ver" => "1.6.4"
        ],
        //"angular"
    ];

    public static function getInstance(): self {
        if(self::$Instance == null) {
            self::$Instance = new static();
        }

        return self::$Instance;
    }

    protected function __construct() {
        add_action("wp_ajax_".self::ACTION_SEND_MAIL,function() {
            $this->actionAjaxCallback();
        });

        add_action("wp_ajax_no_priv".self::ACTION_SEND_MAIL,function() {
            $this->ajaxHandler();
        });

        register_shortcode(self::SHORTCODE,function() {
            $this->shortcodeCallback();
        });

        add_action("wp_enqueue_scripts",function() {
            $this->enqueueAssetsCallback();
        },99);
    }

    protected function enqueueAssetsCallback() {

    }

    protected function shortcodeCallback() {

    }

    protected function actionAjaxCallback() {

    }
}
