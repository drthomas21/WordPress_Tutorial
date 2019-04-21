<?php
/**
* Plugin Name: SWG SEO Plugin
* Author: niiiiisama
* Version: 0.3
* Description: This is a simple SEO plugin to add meta 'description' and 'keywords'
**/
define("SEO_BASE",__DIR__);
require_once(SEO_BASE.'/classes/SeoCore.php');

SeoCore::getInstance();
