<?php
namespace Youtube_Vids\Models;
class AccountModel {
    public static function getAccountRecord(): \Youtube_Vids\Records\AccountRecord {
        $key = "";
        if(defined("API_KEYS")) {
            $isDev = defined("IS_DEV") && IS_DEV ? IS_DEV : false;
            if($isDev) {
                $key = API_KEYS["google-dev"];
            } else {
                $key = API_KEYS["google-prod"];
            }
        }

        $Record = new \Youtube_Vids\Records\AccountRecord($key);
        return $Record;
    }
}
