<?php
namespace Twitter_Feed\Records;

class Tweet {
    var $created_at;
    var $id;
    var $text;
    var $User;

    public function __construct(\stdClass $Obj) {
        $this->created_at = strtotime($Obj->created_at);
        $this->id = $Obj->id;
        $this->text = $Obj->text;
        $this->User = new TwitterUser($Obj->user);
    }
}
