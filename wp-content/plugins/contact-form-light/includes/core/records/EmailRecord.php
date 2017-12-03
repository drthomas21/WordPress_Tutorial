<?php
namespace Contact_Form_Light\Core\Records;
class EmailRecord {
    private $id = 0;
    private $from = "";
    private $subject = "";
    private $ip_address = "";
    private $body = "";

    function __set($prop,$value) {
        if(property_exists($this,$prop)) {
            if(is_int($this->$prop)) {
                $this->$prop = intval($value);
            } else {
                $this->$prop = (string) strip_tags($value);
            }
        }
    }

    function __get($prop) {
        if(property_exists($this,$prop)) {
            return $this->$prop;
        }
    }

    function __toArray(): array {
        return [
            "id" => $this->id,
            "from" => $this->from,
            "subject" => $this->subject,
            "ip_address" => $this->ip_address,
            "body" => $this->body
        ];
    }
}
