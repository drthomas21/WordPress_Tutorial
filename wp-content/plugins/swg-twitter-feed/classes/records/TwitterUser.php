<?php
namespace Twitter_Feed\Records;

class TwitterUser {
    var $id;
    var $screen_name;
    var $url;
    var $profile_image_url_https;

    public function __construct(\stdClass $Obj) {
        $this->id = $Obj->id;
        $this->screen_name = $Obj->screen_name;
        $this->url = $Obj->url;
        $this->profile_image_url_https = $Obj->profile_image_url_https;
    }

}
