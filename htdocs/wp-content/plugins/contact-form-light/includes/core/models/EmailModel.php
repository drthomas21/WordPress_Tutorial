<?php
namespace Contact_Form_Light\Core\Models;
class EmailModel {
    public static function getTablename(): string {
        global $wpdb;
        return $wpdb->prefix."email";
    }

    public static function createTable() {
        global $wpdb;
        $tablename = self::getTablename();

        $wpdb->query("CREATE TABLE IF NOT EXISTS `{$tablename}` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `from` VARCHAR(255) NOT NULL,
            `subject` VARCHAR(255) NOT NULL,
            `ip_address` VARCHAR(20) NOT NULL,
            `body` TEXT NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE INDEX `id_UNIQUE` (`id` ASC));"
        );
    }

    public static function getEmails(int $offset = 0, int $limit = 100): array {
        global $wpdb;
        $list = [];

        $ret = $wpdb->get_results("SELECT * FROM ".self::getTablename()." LIMIT {$offset}, {$limit}");
        foreach($ret as $Item) {
            $Record = new \Contact_Form_Light\Core\Records\EmailRecord();
            foreach(get_object_vars ($Item) as $prop => $value) {
                $Record->$prop = $value;
            }
            if($Record->id > 0) {
                $list[] = $Record;
            }
        }

        return $list;
    }

    public static function getEmail(int $id) {
        global $wpdb;
        $Record = null;
        $Item = $wpdb->get_results("SELECT * FROM ".self::getTablename()." WHERE id={$id}");
        if($Item && is_object($Item)) {
            $Record = new \Contact_Form_Light\Core\Records\EmailRecord();
            foreach(get_object_vars ($Item) as $prop => $value) {
                $Record->$prop = $value;
            }
            if($Record->id == 0) {
                $Record = null;
            }
        }

        return $Record;
    }

    public static function insertEmail(\Contact_Form_Light\Core\Records\EmailRecord $Record) {
        global $wpdb;

        $props = $Record->__toArray();
        unset($props['id']);
        return $wpdb->insert(self::getTablename(),$props);
    }
}
