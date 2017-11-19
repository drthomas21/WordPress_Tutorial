<?php
/**
* Plugin Name: Youtube Profile Vids
* Plugin Author: dathomas
* Version: 0.1a
**/

/**
* NOTE: make sure to add the following to your wp-config.php file
define('API_KEY',[
    "google-dev" => "<key>",
    "google-prod" => "<key>"
]);
**/

//register autoload
spl_autoload_register(function ($class_name) {
    //Ignore class_name that does not match
    if(stripos($class_name,"Youtube_Vids") === false) {
        return;
    }

    //Replace namespace prefix with class directory
    $class_name = str_replace("Youtube_Vids\\","classes".DIRECTORY_SEPARATOR,$class_name);

	$path = preg_replace_callback('!^(.*)?\\\([A-Za-z0-9_]+)$!',function(array $matches): string {
        $matches[1] = str_replace("_","-",$matches[1]);
		return strtolower(str_replace('\\', DIRECTORY_SEPARATOR ,$matches[1])) . DIRECTORY_SEPARATOR . $matches[2];
	},$class_name);

    if(file_exists(__DIR__. DIRECTORY_SEPARATOR . $path . ".php")) {
        include_once(__DIR__. DIRECTORY_SEPARATOR . $path . ".php");
    }
});

//Load Youtube Library
require_once __DIR__.'/vendor/autoload.php';

if(!is_admin()) {
    
}
