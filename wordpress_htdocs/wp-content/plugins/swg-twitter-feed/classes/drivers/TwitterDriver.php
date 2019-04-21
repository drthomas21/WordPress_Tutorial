<?php
namespace Twitter_Feed\Drivers;

class TwitterDriver {
    private $Service;
    private $key;
    private $secret;
    private $bearer_token;

    public function __construct(string $key, string $secret) {
        $this->key = $key;
        $this->secret = $secret;

        $this->Service = new \Twitter_Feed\Services\TwitterService();
    }

    public function authenticateDriver() {
        $data = [
            "grant_type" => "client_credentials"
        ];

        $authToken = base64_encode(rawurlencode($this->key).":".rawurlencode($this->secret));
        $headers = [
            "Authorization: Basic {$authToken}",
            "Content-Type: application/x-www-form-urlencoded;charset=UTF-8"
        ];

        $HTTPResponse = \Twitter_Feed\Services\TwitterService::sendPostRequest("/oauth2/token",$data,$headers);
        $json = json_decode($HTTPResponse->response);
        if($json && property_exists($json,"token_type") && $json->token_type == "bearer") {
            $this->bearer_token = $json->access_token;
        } else {
            throw new \Exception("Failed to authenticate driver: " . print_r($json,true));
        }
    }

    /**
    * @see https://developer.twitter.com/en/docs/tweets/search/overview
    * @param string $query
    **/
    public function searchTweets(string $query): \Twitter_Feed\Records\HTTP\HttpResponse {
        if(strlen($query) <= 0) {
            throw new \InvalidArgumentException("You must provide a query argument");
        }

        if(strlen($this->bearer_token) <= 0) {
            $this->authenticateDriver();
        }
        
        $args = [
            "q" => $query,
            "lang" => "en",
            "count" => 16
        ];

        $headers = [
            "Authorization: Bearer {$this->bearer_token}"
        ];

        $path = "/1.1/search/tweets.json?";
        foreach($args as $param => $val) {
            $path .= "{$param}=".urlencode($val)."&";
        }

        $path = trim($path,"&");

        return \Twitter_Feed\Services\TwitterService::sendGetRequest($path,$headers);
    }
}
