<?php
/**
* Plugin Name: Paypal Ordering
* Version: 0.1a
* Author: dathomas
* Description: This is a simple plugin used to add a shopping chart onto the website and submit the ordering through paypal
**/

require_once __DIR__.'/includes/autoload.php';
require_once __DIR__.'/includes/PaypalApi.php';

PaypalApi::getInstance();
