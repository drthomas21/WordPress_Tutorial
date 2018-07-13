<?php
namespace Twitter_Feed\Records\HTTP;

class HttpResponse {
    var $response;
    var $info;

    public function __construct($response,$info) {
        $this->response = $response;
        $this->info = $info;
    }
}
