<?php
/**
* Plugin Name: Contact Form light
* Author: dathomas
* Version: 0.1
* Description: A simple lightweight contact form that uses angular, bootstrap, and Google's ReCaptcha
**/

spl_autoload_register(function ($class_name) {
    if(stripos($class_name,"Contact_Form_Light\\") !== false) {
        $class_name = preg_replace("/\\?Contact_Form_Light\\/","\\",$class_name);
    }

	$path = preg_replace_callback('!^(.*)?\\\([A-Za-z0-9_]+)$!',function(array $matches): string {
        $matches[1] = str_replace("_","-",$matches[1]);
		return strtolower(str_replace('\\', DIRECTORY_SEPARATOR ,$matches[1])) . DIRECTORY_SEPARATOR . $matches[2];
	},$class_name);

    if(file_exists(__DIR__. DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . $path . ".php")) {
        include_once(__DIR__. DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . $path . ".php");
    }
});
