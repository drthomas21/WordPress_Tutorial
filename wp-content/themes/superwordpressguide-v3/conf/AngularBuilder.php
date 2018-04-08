<?php
namespace Themes\Superwordpressguide_V3\Conf;

class AngularBuilder {
    const SCRIPT_PREFIX = "angularjs-";
    const BASE_ANGULARJS_NAME = "angularjs";
    const EXT_ANGULARJS_PATH = "https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/";
    private static $Instance = null;
    protected $conf = null;

    public static function getInstance():self {
        if(self::$Instance == null) {
            self::$Instance = new self();
        }

        return self::$Instance;
    }

    protected function __construct() {
        if(\isProd()) {
            $this->conf = json_decode(file_get_contents(__DIR__.'/requirements.json'));
        } else {
            $this->conf = json_decode(file_get_contents(__DIR__.'/requirements-dev.json'));
        }

        if($this->conf) {
            \add_action("wp",function() {
                $this->registerAngularModules();
            },9);
            \add_action("wp_enqueue_scripts",function() {
                $this->enqueueAngularModules();
            },9);
            \add_action("wp_footer",function() {
                $this->buildAngularApp();
            },99);
        }
    }

    protected function registerAngularModules() {
        if($this->conf->angularjs->isMin) {
            \wp_register_script(self::BASE_ANGULARJS_NAME,self::EXT_ANGULARJS_PATH ."angular.min.js",['jquery'],THEME_VERSION,true);
            foreach($this->conf->angularjs->modules as $module) {
                $assetFile = strtolower(str_replace("ng","angular-",$module).".min.js");
                \wp_register_script(self::SCRIPT_PREFIX.$module,self::EXT_ANGULARJS_PATH . $assetFile,[self::BASE_ANGULARJS_NAME],THEME_VERSION,true);
            }
        } else {
            \wp_register_script(self::BASE_ANGULARJS_NAME,self::EXT_ANGULARJS_PATH ."angular.js",['jquery'],THEME_VERSION,true);
            foreach($this->conf->angularjs->modules as $module) {
                $assetFile = strtolower(str_replace("ng","angular-",$module).".min.js");
                \wp_register_script(self::SCRIPT_PREFIX.$module,self::EXT_ANGULARJS_PATH . $assetFile,[self::BASE_ANGULARJS_NAME],THEME_VERSION,true);
            }
        }
    }

    protected function enqueueAngularModules() {
        \wp_enqueue_script(self::BASE_ANGULARJS_NAME);
        foreach($this->conf->angularjs->modules as $module) {
            \wp_enqueue_script(self::SCRIPT_PREFIX.$module);
        }
    }

    protected function buildAngularApp() {
        //Run AngularJS init script
        echo "
        <!-- Start AngularJS Init -->
        <script type='text/javascript'>(function(){
            var app = angular.module('{$this->conf->angularjs->name}',".json_encode($this->conf->angularjs->modules).");
        ";
        include get_template_directory()."/assets/js/app.js";

        echo "
        })();</script>
        <!-- End AngularJS Init -->
        ";
    }
}
