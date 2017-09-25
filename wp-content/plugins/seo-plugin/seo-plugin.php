<?php
/**
* Plugin Name: SEO Plugin
* Author: dathomas
* Version: 0.2a
* Description: This is a simple SEO plugin to add meta 'description' and 'keywords'
**/
define("SEO_BASE",__DIR__);
require_once(SEO_BASE.'/classes/SeoCore.php');

SeoCore::getInstance();
