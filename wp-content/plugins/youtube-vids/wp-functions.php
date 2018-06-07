<?php
/**
* Defining globally available plugins
**/

function get_popular_videos(int $offset = 0, int $limit = 10): array {
    $list = wp_cache_get(__FUNCTION__,YOUTUBE_VIDS_CACHE_GROUP);

    $shouldFetchFromGoogle = !is_array($list) || empty($list) || empty(array_slice($list,$offset,$limit));
    if(!$shouldFetchFromGoogle) {
        //Used to catch missing elements in the array
        foreach(array_slice($list,$offset,$limit) as $Item) {
            if($Item == null) {
                $shouldFetchFromGoogle = true;
                break;
            }
        }
    }

    if($shouldFetchFromGoogle) {
        $Controller = new \Youtube_Vids\Controllers\PageController();
        $segment = $Controller->getPopularVideos($offset,$limit);

        //Instead of doing 'array_merge' we are merging this array as if it has numerical keys instead of associative keys
        $i = $offset;
        while(!empty($segment)) {
            $list[$i] = array_shift($segment);
            $i++;
        }

        //We do not want to cache empty list
        if(!empty($list)) {
            wp_cache_set(__FUNCTION__,$list,YOUTUBE_VIDS_CACHE_GROUP,86400);
        }
    }


    return array_slice($list,$offset,$limit);
}

function get_recent_videos(int $offset = 0, int $limit = 10): array {
    $list = wp_cache_get(__FUNCTION__,YOUTUBE_VIDS_CACHE_GROUP);

    $shouldFetchFromGoogle = !is_array($list) || empty($list) || empty(array_slice($list,$offset,$limit));
    if(!$shouldFetchFromGoogle) {
        //Used to catch missing elements in the array
        foreach(array_slice($list,$offset,$limit) as $Item) {
            if($Item == null) {
                $shouldFetchFromGoogle = true;
                break;
            }
        }
    }

    if($shouldFetchFromGoogle) {
        $Controller = new \Youtube_Vids\Controllers\PageController();
        $segment = $Controller->getRecentVideos($offset,$limit);

        //Instead of doing 'array_merge' we are merging this array as if it has numerical keys instead of associative keys
        $i = $offset;
        while(!empty($segment)) {
            $list[$i] = array_shift($segment);
            $i++;
        }

        //We do not want to cache empty list
        if(!empty($list)) {
            wp_cache_set(__FUNCTION__,$list,YOUTUBE_VIDS_CACHE_GROUP,86400);
        }
    }

    return array_slice($list,$offset,$limit);
}
