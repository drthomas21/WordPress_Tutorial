<?php
namespace Twitter_Feed\Services;
class TwitterService {
    private const HTTP_METHOD_GET = "GET";
    private const HTTP_METHOD_POST = "POST";
    private const TWITTER_BASE = "https://api.twitter.com";

    public function __construct() {

    }

    protected static function sendRequest(string $method, string $url, array $data, array $headers): \Twitter_Feed\Records\HTTP\HttpResponse {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
        curl_setopt($ch,CURLOPT_MAXREDIRS,5);
        curl_setopt($ch,CURLINFO_HEADER_OUT,true);

        if(!empty($headers)) {
            curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        }

        if($method == self::HTTP_METHOD_POST) {
            $httpData = http_build_query($data);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$httpData);
        }

        $ret = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        $Response = new \Twitter_Feed\Records\HTTP\HttpResponse($ret,$info);
        return $Response;
    }

    public static function sendGetRequest(string $path = "/", array $headers = []): \Twitter_Feed\Records\HTTP\HttpResponse {
        $url = self::TWITTER_BASE . "/" . trim($path,"/");

        return self::sendRequest(self::HTTP_METHOD_GET,$url,[],$headers);

    }

    public static function sendPostRequest(string $path = "/", array $data = [], array $headers = []): \Twitter_Feed\Records\HTTP\HttpResponse {
        $url = self::TWITTER_BASE . "/" . trim($path,"/");

        return self::sendRequest(self::HTTP_METHOD_POST,$url,$data,$headers);
    }
}
