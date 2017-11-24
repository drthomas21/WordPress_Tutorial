<?php
/**
* Plugin Name: Youtube Profile Vids
* Description: A custom plugin used to fetch account data about the registered account. Please make sure to set your API key like below
define("OAUTH_KEYS",[
	"google" => [
		"id" => "<id>",
		"secret" => "<secret>"
	]
]);
* Author: dathomas
* Version: 0.3
**/

/**
* NOTE: make sure to add the following to your wp-config.php file
define("OAUTH_KEYS",[
	"google" => [
		"id" => "<id>",
		"secret" => "<secret>"
	]
]);
**/

define("YOUTUBE_VIDS_DIR",__DIR__);
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
\Youtube_Vids\Controllers\AdminController::getInstance();

function list_popular_videos(int $offset = 0, int $limit = 10): array {
    $maxNum = $offset + $limit;
    $list = wp_cache_get("popularVideos","Youtube");
    if(!$list || empty($list) || count($list) < $maxNum) {
        $Controller = new \Youtube_Vids\Controllers\PageController();
        $list = $Controller->getPopularVideos($offset,$limit);

        wp_cache_set("popularVideos",$list,"Youtube",604800);
    }
    return array_slice($list,$offset,$limit);
}

function list_recent_videos(int $offset = 0, int $limit = 10): array {
    $maxNum = $offset + $limit;
    $list = wp_cache_get("recentVideos","Youtube");
    if(!$list || empty($list) || count($list) < $maxNum) {
        $Controller = new \Youtube_Vids\Controllers\PageController();
        $list = $Controller->getRecentVideos($offset,$limit);

        wp_cache_set("recentVideos",$list,"Youtube",604800);
    }
    return array_slice($list,$offset,$limit);
}

function get_popular_videos(int $offset = 0, int $limit = 10): string {
    $videos = list_popular_videos($offset,$limit);
    $items = [];
    if(!empty($videos)) {
        foreach ($videos as $Video) {
            $items[] = "<li><iframe src='https://www.youtube.com/embed/{$Video->id}'></iframe></li>";
        }
    }

    return "<ul>".implode("",$items)."</ul>";
}

function get_recent_videos(int $offset = 0, int $limit = 10): string {
    $videos = list_recent_videos($offset,$limit);
    $items = [];
    if(!empty($videos)) {
        foreach ($videos as $Video) {
            $items[] = "<li><iframe src='https://www.youtube.com/embed/{$Video->id}'></iframe></li>";
        }
    }

    return "<ul>".implode("",$items)."</ul>";
}

function the_popular_videos(int $offset = 0, int $limit = 10) {
    echo get_popular_videos($offset,$limit);
}

function the_newest_videos(int $offset = 0, int $limit = 10) {
    echo get_newest_videos($offset,$limit);
}
