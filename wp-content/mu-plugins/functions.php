<?php
function get_keywords(WP_Post $Post = null, int $limit = 15): array {
    global $post;
    $cacheKey = "banned_words";
    $cacheGroup = "seo";

    if($Post == null) {
        $Post = $post;
    }

    $arrBannedWords = wp_cache_get($cacheKey,$cacheGroup);
    if(!$arrBannedWords || !is_array($arrBannedWords)) {
        $arrBannedWords = [];
        $file = fopen(__DIR__.'/inc/banned-words.txt','r');
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
        if(!in_array($val,$arrBannedWords)) {
            if(!array_key_exists($val,$map)) {
                $map[$val] = 0;
            }

            $map[$val]++;
        }
    });

    arsort($map);
    $ret = array_slice($map,0,$limit);
    return array_keys($ret);
}

function get_the_keywords(WP_Post $Post = null, int $limit = 15): string {
    $ret = get_keywords($Post,$limit);
    return implode(",",$ret);

}

function the_keywords(WP_Post $Post = null, int $limit = 15) {
    echo get_the_keywords($Post,$limit);
}
