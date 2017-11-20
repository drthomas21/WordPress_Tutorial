<?php
namespace Youtube_Vids\Models;
class AccountModel {
    public static function getAccountRecord(): \Youtube_Vids\Records\AccountRecord {
        $id = "";
        $secret = "";
        if(defined("OAUTH_KEYS")) {
            if(array_key_exists('google',OAUTH_KEYS)) {
                $id = OAUTH_KEYS['google']['id'];
                $secret = OAUTH_KEYS['google']['secret'];
            } else {
                error_log("please add your 'google' OAUTH key into your list of 'OAUTH_KEYS'");
            }
        } else {
            error_log("'OAUTH_KEYS' is not set");
        }

        $Record = new \Youtube_Vids\Records\AccountRecord($id,$secret);
        return $Record;
    }
}
