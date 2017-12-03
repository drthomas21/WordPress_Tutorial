<?php
namespace Contact_Form_Light\Core\Controllers;
class ModuleController {
    public static $Instance = null;

    public static function getInstance(): self {
        if(self::$Instance == null) {
            self::$Instance = new self();
        }

        return self::$Instance;
    }

    protected function __construct() {
        $baseModuleDir = CONTACT_FORM_LIGHT_DIR.'/includes/modules';
        $folders = scandir($baseModuleDir);
        foreach($folders as $folder) {
            if($folder != "." && $folder != ".." && is_dir($baseModuleDir.'/'.$folder)) {
                $moduleDir = $baseModuleDir.'/'.$folder;
                $files = scandir($moduleDir);
                foreach($files as $file) {
                    if(stripos($moduleDir.'/'.$file,"Module.php") !== false) {
                        $classname = "\\Contact_Form_Light\\Modules\\".ucwords($folder)."\\".str_replace(".php","",$file);
                        new $classname();
                    }
                }
            }
        }
    }
}
