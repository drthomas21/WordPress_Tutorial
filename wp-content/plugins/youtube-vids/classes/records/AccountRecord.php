<?php
namespace Youtube_Vids\Records;
class AccountRecord {
    //Create a map of properites that can be accessed
    // [label] => [prop]
    private static $props = [
        "apiKey" => "apiKey"
    ];

    private $apiKey = "";

    public function __construct(string $key) {
        $this->apiKey = $key;
    }

    //Only allow read access to properties in $props
    public function __get($name): string {
        if(array_key_exists($name,self::$props)) {
            $prop = self::$prop[$name];
            return $this->$prop;
        }
    }
}
