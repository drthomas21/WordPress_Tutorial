<?php
/**
* Plugin Name: SEO Plugin
* Author: dathomas
* Version: 0.2a
* Description: This is a simple SEO plugin to add meta 'description' and 'keywords'
**/
function get_keywords(int $limit = 10, WP_Post $Post = null): string {
    global $post;
    $cacheKey = "banned_words";
    $cacheGroup = "seo";

    if($Post == null) {
        $Post = $post;
    }

    $arrBannedWords = wp_cache_get($cacheKey,$cacheGroup);
    if(!$arrBannedWords || !is_array($arrBannedWords)) {
        $arrBannedWords = [];
        $file = fopen(__DIR__.'/banned-words.txt','r');
        while(!feof($file)) {
            $line = fgets($file);
            if(strpos($line,"#") === 0) continue;
            $arrBannedWords[] = trim($line);
        }
        fclose($file);
        wp_cache_set($cacheKey,$arrBannedWords,$cacheGroup,86400);
    }

    $words = str_word_count(strip_tags($Post->post_content),1);
    $map = [];

    array_walk($words,function($word) use (&$map, $arrBannedWords) {
        $val = strtolower($word);
        if(!in_array($word,$arrBannedWords)) {
            if(!array_key_exists($word,$map)) {
                $map[$val] = 0;
            }

            $map[$val]++;
        }
    });

    arsort($map);
    $ret = array_slice($map,0,$limit);
    return implode(",",array_keys($ret));
}

add_action("wp_head",function() {
    echo "<!-- Start SEO Plugin -->" . PHP_EOL;
    include __DIR__.'/templates/analytics.php';
    if(is_single()) {
        global $post;
        include __DIR__.'/templates/metadata-post.php';
    } else {
        include __DIR__.'/templates/metadata-default.php';
    }
    echo "<!-- End SEO Plugin -->" . PHP_EOL;
});
