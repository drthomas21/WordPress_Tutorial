<?php
/**
* Plugin Name: Contact Form light
* Author: dathomas
* Version: 0.2
* Description: Use the shortcode <strong>[contact_form_light]</strong> to add the contact form. A simple lightweight contact form that uses angular, bootstrap, and Google's reCAPTCHA.
**/
define('CONTACT_FORM_LIGHT_DIR',__DIR__);
define('CONTACT_PLUGIN_NAME', "Contact Form Light");

spl_autoload_register(function ($class_name) {
    if(stripos($class_name,"Contact_Form_Light\\") !== false) {
        $class_name = preg_replace("/".preg_quote("\\") ."?Contact_Form_Light".preg_quote("\\") ."/","",$class_name);
    }

	$path = preg_replace_callback('!^(.*)?\\\([A-Za-z0-9_]+)$!',function(array $matches): string {
        $matches[1] = str_replace("_","-",$matches[1]);
		return strtolower(str_replace('\\', DIRECTORY_SEPARATOR ,$matches[1])) . DIRECTORY_SEPARATOR . $matches[2];
	},$class_name);


    if(file_exists(__DIR__. DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . $path . ".php")) {
        include_once(__DIR__. DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . $path . ".php");
    }
});

\Contact_Form_Light\Core\Drivers\WPContactDriver::getInstance();

if(is_admin()) {
    \Contact_Form_Light\Core\Controllers\AdminController::getInstance();
}

register_activation_hook(__FILE__,function() {
    \Contact_Form_Light\Core\Drivers\WPContactDriver::getInstance()->activation();
});

\Contact_Form_Light\Core\Controllers\ModuleController::getInstance();
