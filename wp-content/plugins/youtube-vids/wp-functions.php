<?php
/**
* Defining globally available plugins
**/

function get_popular_videos(int $offset = 0, int $limit = 10): array {
    $list = wp_cache_get(__FUNCTION__,YOUTUBE_VIDS_CACHE_GROUP);
    $Controller = new \Youtube_Vids\Controllers\PageController();

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
        $segment = $Controller->getPopularVideos($offset,$limit*2);

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

    $flaggedIds = $Controller->getFlaggedIds();
    $flaggedIds = $flaggedIds[\Youtube_Vids\Controllers\PageController::LABEL_POPULAR];
    $list = array_filter($list,function($Item) use($flaggedIds) {
        return !in_array($Item->id,$flaggedIds);
    });

    $temp = array_slice($list,$offset,$limit);
    if(count($temp) != $limit) {
        $shouldFetchFromGoogle = true;
    }

    return array_slice($list,$offset,$limit);
}

function get_recent_videos(int $offset = 0, int $limit = 10): array {
    $Controller = new \Youtube_Vids\Controllers\PageController();
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
        $segment = $Controller->getRecentVideos($offset,$limit*2);

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

    $flaggedIds = $Controller->getFlaggedIds();
    $flaggedIds = $flaggedIds[\Youtube_Vids\Controllers\PageController::LABEL_RECENT];
    $list = array_filter($list,function($Item) use($flaggedIds) {
        return !in_array($Item->id,$flaggedIds);
    });

    $temp = array_slice($list,$offset,$limit);
    if(count($temp) != $limit) {
        $shouldFetchFromGoogle = true;
    }

    return array_slice($list,$offset,$limit);
}
