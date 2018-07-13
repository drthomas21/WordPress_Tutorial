<?php
namespace Youtube_Vids\Records;
abstract class BaseRecord {
    //Create a map of properites that can be accessed
    // [label] => [prop]
    protected $props = [];

    //Only allow read access to properties in $props
    public function __get($name) {

        if(array_key_exists($name,$this->props)) {
            $prop = $this->props[$name];
            $value = $this->$prop;
            return $value;
        }

        return "";
    }
}
