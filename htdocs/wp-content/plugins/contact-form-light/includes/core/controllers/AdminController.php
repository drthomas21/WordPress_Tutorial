<?php
namespace Contact_Form_Light\Core\Controllers;
class AdminController {
    const ACTION_ADMIN_TOP = "contact_admin_top";
    const ACTION_ADMIN_BOTTOM = "contact_admin_bottom";

    public static $Instance = null;

    public static function getInstance(): self {
        if(self::$Instance == null) {
            self::$Instance = new self();
        }

        return self::$Instance;
    }

    protected function __construct() {
        add_action("admin_menu",function() {
            add_options_page(
                CONTACT_PLUGIN_NAME,
                CONTACT_PLUGIN_NAME,
                'manage_options',
                str_replace(" ","-",strtolower(CONTACT_PLUGIN_NAME)),
                function() {
                    $this->display();
                }
            );
        });
    }

    protected function display() {
        $offset = $_GET['offset'] ? intval($_GET['offset']) : 0;
        $limit = $_GET['limit'] ? intval($_GET['limit']) : 100;

        $Records = \Contact_Form_Light\Core\Models\EmailModel::getEmails($offset,$limit);
        include CONTACT_FORM_LIGHT_DIR.'/templates/admin.php';
    }
}
