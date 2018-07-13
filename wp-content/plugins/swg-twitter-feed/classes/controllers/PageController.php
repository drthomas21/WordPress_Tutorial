<?php
namespace Twitter_Feed\Controllers;

class PageController {
    private $Driver = null;

    public function __construct() {
        if(defined("OAUTH_KEYS") && array_key_exists("twitter",OAUTH_KEYS)) {
            $oauthCreds = OAUTH_KEYS['twitter'];
            $this->Driver = new \Twitter_Feed\Drivers\TwitterDriver($oauthCreds['key'],$oauthCreds['secret']);
        }
    }

    public function searchTweets(string $query): array {
        $Tweets = wp_cache_get("search_tweets_".md5($query),TWITTER_FEED_CACHE_GROUP);
        if(is_array($Tweets) && !empty($Tweets)) {
            return $Tweets;
        }

        if(!$this->Driver) {
            return $Tweets;
        }

        $HTTPResponse = $this->Driver->searchTweets($query);
        $code = $HTTPResponse->info['http_code'];
        if($code == 200) {
            $json = json_decode($HTTPResponse->response);
            $Tweets = [];
            if($json && array_key_exists("statuses",$json)) {
                foreach($json->statuses as $status) {
                    $Tweets[] = new \Twitter_Feed\Records\Tweet($status);
                }
            }
        } else {
            error_log(print_r($json,true));
        }

        wp_cache_set("search_tweets_".md5($query),$Tweets,TWITTER_FEED_CACHE_GROUP,30);
        return $Tweets;
    }
}
