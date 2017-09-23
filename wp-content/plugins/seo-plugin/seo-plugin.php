<?php
/**
* Plugin Name: SEO Plugin
* Author: dathomas
* Version: 0.1a
* Description: This is a simple SEO plugin to add meta 'description' and 'keywords'
**/
function get_keywords(WP_Post $Post, int $limit = 10): string {
    $arrBannedWords = ['a','the','in','for','or','sit'];
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
    if(is_single()) {
        global $post;
        echo "<meta name='description' content='".strip_tags(get_the_excerpt())."' >" . PHP_EOL;
        echo "<meta name='keywords' content='".get_keywords($post,15)."' >" . PHP_EOL;
    }
    echo "<!-- End SEO Plugin -->" . PHP_EOL;
});
