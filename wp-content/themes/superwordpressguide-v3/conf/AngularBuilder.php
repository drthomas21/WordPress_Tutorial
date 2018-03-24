<?php
namespace Themes\Superwordpressguide_V2\Conf;

class AngularBuilder {
    const SCRIPT_PREFIX = "angularjs-";
    const BASE_ANGULARJS_NAME = "angularjs";
    private static $Instance = null;
    protected $conf = null;

    public static function getInstance() {
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
            });
            \add_action("wp_enqueue_scripts",function() {
                $this->enqueueAngularModules();
            })
            \add_action("wp_head",function() {
                $this->buildAngularApp();
            });
        }
    }

    protected function registerAngularModules() {
        if($this->config->isMin) {
            \wp_register_script(self::BASE_ANGULARJS_NAME,get_asset_url("js/angular.min.js"),['jquery'],THEME_VERSION,true);
            foreach($this->config->modules as $module) {
                $assetFile = "js/".strtolower(str_replace("ng","angular-",$module)."min.js");
                \wp_register_script(self::SCRIPT_PREFIX.$module,get_asset_url($assetFile),[self::BASE_ANGULARJS_NAME],THEME_VERSION,true);
            }
        } else {
            \wp_register_script(self::BASE_ANGULARJS_NAME,get_asset_url("js/angular.js"),['jquery'],THEME_VERSION,true);
            foreach($this->config->modules as $module) {
                $assetFile = "js/".strtolower(str_replace("ng","angular-",$module)."js");
                \wp_register_script(self::SCRIPT_PREFIX.$module,get_asset_url($assetFile),[self::BASE_ANGULARJS_NAME],THEME_VERSION,true);
            }
        }
    }

    protected function enqueueAngularModules() {
        \wp_enqueue_script(self::BASE_ANGULARJS_NAME);
        foreach($this->config->modules as $module) {
            \wp_enqueue_script(self::SCRIPT_PREFIX.$module);
        }
    }

    protected function buildAngularApp() {
        //Run AngularJS init script
        echo "<script type='text/javascript'>(function(){
            var init = function(num) {
                if(typeof angular == 'undefined' && num < 100) {
                    setTimeout(function(){
                        init(num+1)
                    },100);
                } else if(num < 100) {
                    loadAngularApp();
                }
            };

            var loadAngularApp = function() {
                window.app = angular.module('{$this->conf->name}',".json_encode($this->conf->modules).");
            };

            init();
        })();</script>";
    }
}
